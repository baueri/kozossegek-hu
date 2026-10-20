<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Webhook;

use App\Models\ApiToken;
use App\Models\ChurchGroup;
use App\Portal\Services\Api\GroupApiTransformer;
use App\QueryBuilders\ApiTokens;
use App\QueryBuilders\ChurchGroupViews;
use App\Services\Webhook\GroupWebhookDispatcher;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class GroupWebhookDispatcherTest extends TestCase
{
    public function testSignMatchesHmacSha256HeaderFormat(): void
    {
        $body = '{"event":"group.deleted","data":{"id":9}}';
        $secret = 'kzh_whsec_testsecret';
        $sig = GroupWebhookDispatcher::sign($body, $secret);

        $this->assertSame('sha256=' . hash_hmac('sha256', $body, $secret), $sig);
        $this->assertTrue(hash_equals($sig, GroupWebhookDispatcher::sign($body, $secret)));
    }

    public function testDeletedEventPostsSignedPayloadWithId(): void
    {
        $history = [];
        $mock = new MockHandler([new Response(200)]);
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($history));
        $client = new Client(['handler' => $handler]);

        /** @var ApiTokens&MockObject $apiTokens */
        $apiTokens = $this->createMock(ApiTokens::class);
        $token = ApiToken::make([
            'id' => 3,
            'user_id' => 1,
            'webhook_url' => 'https://partner.example/hooks',
            'webhook_secret' => 'kzh_whsec_testsecret12',
        ]);
        $apiTokens->method('withWebhookForUsers')->willReturn([$token]);

        /** @var ChurchGroupViews&MockObject $views */
        $views = $this->createMock(ChurchGroupViews::class);

        $dispatcher = new GroupWebhookDispatcher(
            $apiTokens,
            $views,
            new GroupApiTransformer(),
            $client,
        );

        $group = ChurchGroup::make(['id' => 42, 'user_id' => 1, 'name' => 'Teszt']);

        // Avoid DB call for managed_church_groups by using snapshot-only path still needs recipientTokens
        // which hits db()->select. Skip if DB unavailable.
        try {
            db()->first('select 1');
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available for webhook dispatch.');
        }

        try {
            db()->first('select 1 from managed_church_groups limit 1');
        } catch (\Throwable) {
            $this->markTestSkipped('managed_church_groups table not available.');
        }

        $dispatcher->dispatch(
            GroupWebhookDispatcher::EVENT_DELETED,
            $group,
            ['id' => 42, 'name' => 'Teszt'],
        );

        $this->assertCount(1, $history);
        $request = $history[0]['request'];
        $raw = (string) $request->getBody();
        $payload = json_decode($raw, true);

        $this->assertSame('group.deleted', $payload['event']);
        $this->assertSame(42, $payload['data']['id']);
        $this->assertSame('application/json', $request->getHeaderLine('Content-Type'));
        $this->assertSame('group.deleted', $request->getHeaderLine('X-Kozossegek-Event'));
        $this->assertNotEmpty($request->getHeaderLine('X-Kozossegek-Delivery'));
        $this->assertSame(
            GroupWebhookDispatcher::sign($raw, 'kzh_whsec_testsecret12'),
            $request->getHeaderLine('X-Kozossegek-Signature'),
        );
    }
}

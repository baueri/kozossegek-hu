<?php

declare(strict_types=1);

namespace App\Services\Webhook;

use App\Models\ApiToken;
use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use App\Portal\Services\Api\GroupApiTransformer;
use App\QueryBuilders\ApiTokens;
use App\QueryBuilders\ChurchGroupViews;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

final class GroupWebhookDispatcher
{
    public const EVENT_CREATED = 'group.created';
    public const EVENT_UPDATED = 'group.updated';
    public const EVENT_DELETED = 'group.deleted';

    public function __construct(
        private readonly ApiTokens $apiTokens,
        private readonly ChurchGroupViews $groupViews,
        private readonly GroupApiTransformer $transformer,
        private readonly ?Client $httpClient = null,
    ) {
    }

    public function dispatch(string $event, ChurchGroup $group, ?array $deletedSnapshot = null): void
    {
        try {
            $tokens = $this->recipientTokens($group);
            if ($tokens === []) {
                return;
            }

            $data = $this->payloadData($event, $group, $deletedSnapshot);
            $deliveredAt = gmdate('c');

            foreach ($tokens as $token) {
                $this->deliver($token, $event, $deliveredAt, $data);
            }
        } catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * @return list<ApiToken>
     */
    private function recipientTokens(ChurchGroup $group): array
    {
        $userIds = [(int) $group->user_id];

        $managed = db()->select(
            'select user_id from managed_church_groups where group_id = ?',
            [$group->getId()]
        );
        foreach ($managed as $row) {
            $userIds[] = (int) $row['user_id'];
        }

        return $this->apiTokens->withWebhookForUsers($userIds);
    }

    private function payloadData(string $event, ChurchGroup $group, ?array $deletedSnapshot): array
    {
        if ($event === self::EVENT_DELETED) {
            if ($deletedSnapshot !== null) {
                return $deletedSnapshot;
            }

            return ['id' => (int) $group->getId()];
        }

        /** @var ChurchGroupView|null $view */
        $view = $this->groupViews->with('tags')->find($group->getId());

        return $view
            ? $this->transformer->transformView($view)
            : $this->transformer->transformEntity($group);
    }

    public function snapshotForDelete(ChurchGroup $group): array
    {
        /** @var ChurchGroupView|null $view */
        $view = $this->groupViews->with('tags')->find($group->getId());

        return $view
            ? $this->transformer->transformView($view)
            : $this->transformer->transformEntity($group);
    }

    private function deliver(ApiToken $token, string $event, string $deliveredAt, array $data): void
    {
        $url = (string) $token->webhook_url;
        $secret = (string) $token->webhook_secret;
        if ($url === '' || $secret === '') {
            return;
        }

        $deliveryId = $this->uuid();
        $body = json_encode([
            'event' => $event,
            'delivered_at' => $deliveredAt,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($body === false) {
            return;
        }

        $signature = 'sha256=' . hash_hmac('sha256', $body, $secret);

        try {
            $this->client()->post($url, [
                'body' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Kozossegek-Event' => $event,
                    'X-Kozossegek-Delivery' => $deliveryId,
                    'X-Kozossegek-Signature' => $signature,
                ],
                'http_errors' => false,
                'timeout' => 5,
                'connect_timeout' => 5,
            ]);
        } catch (GuzzleException $e) {
            error_log(sprintf(
                'Webhook delivery failed [%s] token=%d url=%s: %s',
                $event,
                (int) $token->getId(),
                $url,
                $e->getMessage()
            ));
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function client(): Client
    {
        return $this->httpClient ?? new Client();
    }

    public static function sign(string $rawBody, string $secret): string
    {
        return 'sha256=' . hash_hmac('sha256', $rawBody, $secret);
    }

    private function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}

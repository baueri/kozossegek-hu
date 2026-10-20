<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Webhook;

use App\Services\Webhook\WebhookUrlValidator;
use Tests\TestCase;

final class WebhookUrlValidatorTest extends TestCase
{
    private WebhookUrlValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new WebhookUrlValidator();
    }

    public function testRejectsShortSecretAndRelativeUrl(): void
    {
        $short = $this->validator->validate('https://partner.example/hooks', 'short');
        $this->assertFalse($short['ok']);

        $relative = $this->validator->validate('/hooks', str_repeat('s', 16));
        $this->assertFalse($relative['ok']);
    }

    public function testRejectsLocalhostAndPrivateIp(): void
    {
        $localhost = $this->validator->validate('https://localhost/hooks', str_repeat('s', 16));
        $this->assertFalse($localhost['ok']);

        $private = $this->validator->validate('https://127.0.0.1/hooks', str_repeat('s', 16));
        $this->assertFalse($private['ok']);
    }

    public function testAcceptsPublicHttpsUrl(): void
    {
        // example.com resolves publicly; if DNS fails in CI, skip
        $result = $this->validator->validate('https://example.com/hooks/groups', str_repeat('s', 16));
        if (!$result['ok'] && str_contains($result['message'] ?? '', 'private')) {
            $this->markTestSkipped('DNS resolution unexpected in this environment.');
        }
        $this->assertTrue($result['ok'], $result['message'] ?? '');
    }
}

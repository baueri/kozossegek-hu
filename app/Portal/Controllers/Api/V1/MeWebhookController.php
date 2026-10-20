<?php

declare(strict_types=1);

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\QueryBuilders\ApiTokens;
use App\Services\Webhook\WebhookUrlValidator;
use Framework\Http\Request;

class MeWebhookController
{
    public function show(): array
    {
        $token = Auth::apiToken();
        if (!$token) {
            return api()->error(['message' => 'No API token in request context'], 401);
        }

        $attrs = $token->getAttributes();
        $url = $attrs['webhook_url'] ?? null;
        $host = null;
        if ($url) {
            $host = parse_url($url, PHP_URL_HOST) ?: null;
        }

        return api()->ok([
            'data' => [
                'url' => $url,
                'host' => $host,
                'registered_at' => $attrs['webhook_registered_at'] ?? null,
            ],
        ]);
    }

    public function update(Request $request, ApiTokens $apiTokens, WebhookUrlValidator $validator): array
    {
        $token = Auth::apiToken();
        if (!$token) {
            return api()->error(['message' => 'No API token in request context'], 401);
        }

        $url = (string) $request->get('url', '');
        $secret = (string) $request->get('secret', '');

        $validation = $validator->validate($url, $secret);
        if (!$validation['ok']) {
            return api()->error(['message' => $validation['message']], 422);
        }

        $apiTokens->saveWebhook($token, trim($url), trim($secret));

        return api()->ok([
            'data' => [
                'url' => $token->webhook_url,
            ],
        ]);
    }

    public function destroy(ApiTokens $apiTokens): array
    {
        $token = Auth::apiToken();
        if (!$token) {
            return api()->error(['message' => 'No API token in request context'], 401);
        }

        $apiTokens->clearWebhook($token);

        return api()->ok(['message' => 'Webhook removed']);
    }
}

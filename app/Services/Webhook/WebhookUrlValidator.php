<?php

declare(strict_types=1);

namespace App\Services\Webhook;

final class WebhookUrlValidator
{
    private const MIN_SECRET_LENGTH = 16;

    /**
     * @return array{ok: true}|array{ok: false, message: string}
     */
    public function validate(string $url, string $secret): array
    {
        $url = trim($url);
        $secret = trim($secret);

        if ($url === '') {
            return ['ok' => false, 'message' => 'url is required'];
        }

        if (strlen($secret) < self::MIN_SECRET_LENGTH) {
            return ['ok' => false, 'message' => 'secret must be at least ' . self::MIN_SECRET_LENGTH . ' characters'];
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return ['ok' => false, 'message' => 'url must be an absolute URL'];
        }

        $scheme = strtolower((string) $parts['scheme']);

        if (is_prod() && $scheme !== 'https') {
            return ['ok' => false, 'message' => 'url must use https in production'];
        }

        if (!in_array($scheme, ['https', 'http'], true)) {
            return ['ok' => false, 'message' => 'url scheme must be https (or http in non-production)'];
        }

        // SSRF guards: block localhost / private networks in production only.
        // Local/dev partners (e.g. https://localhost:8443/wp-json/...) must be able to register.
        if (is_prod()) {
            $host = strtolower((string) $parts['host']);
            if ($this->isBlockedHost($host)) {
                return ['ok' => false, 'message' => 'url host is not allowed'];
            }

            $ips = $this->resolveHostIps($host);
            foreach ($ips as $ip) {
                if ($this->isPrivateIp($ip)) {
                    return ['ok' => false, 'message' => 'url must not point to a private network'];
                }
            }
        }

        return ['ok' => true];
    }

    private function isBlockedHost(string $host): bool
    {
        if ($host === 'localhost' || str_ends_with($host, '.localhost') || $host === '0.0.0.0') {
            return true;
        }

        if (filter_var($host, FILTER_VALIDATE_IP) && $this->isPrivateIp($host)) {
            return true;
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private function resolveHostIps(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $records = @dns_get_record($host, DNS_A + DNS_AAAA);
        if (!is_array($records)) {
            $ipv4 = @gethostbynamel($host) ?: [];

            return array_values(array_filter($ipv4));
        }

        $ips = [];
        foreach ($records as $record) {
            if (!empty($record['ip'])) {
                $ips[] = $record['ip'];
            }
            if (!empty($record['ipv6'])) {
                $ips[] = $record['ipv6'];
            }
        }

        return array_values(array_unique($ips));
    }

    private function isPrivateIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return !filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return !filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );
        }

        return true;
    }
}

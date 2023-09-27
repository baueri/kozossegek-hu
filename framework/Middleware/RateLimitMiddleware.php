<?php

namespace Framework\Middleware;

use Carbon\Carbon;

class RateLimitMiddleware implements Middleware
{
    /**
     * @param int $limit allowed requests per throttle
     * @param int $throttle in seconds
     */
    public function __construct(
        private readonly int $limit = 180,
        private readonly int $throttle = 60
    ) {
    }

    public function handle(): void
    {
        // a middleware that checks if the user has exceeded the rate limit
        // if so, it will deny the request

        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $throttleRequest = builder('throttle_request')
            ->where('ip', $ip)
            ->where('user_agent', $userAgent)
            ->first();

        if ($throttleRequest) {
            if (strtotime('now') - strtotime($throttleRequest['created_at']) < $this->throttle && $throttleRequest['count'] >= $this->limit) {
                $throttleRequest['expires_at'] = now()->addMinutes(15);
                builder('throttle_request')->where('id', $throttleRequest['id'])->update($throttleRequest);
                http_response_code(429);
                echo 'Too many requests, please come back later.';
                exit;
            } elseif ($throttleRequest['expires_at'] && Carbon::parse($throttleRequest['expires_at'])->isPast()) {
                builder('throttle_request')->where('id', $throttleRequest['id'])->delete();
            } else {
                $throttleRequest['count']++;
                builder('throttle_request')->where('id', $throttleRequest['id'])->update($throttleRequest);
            }
        } else {
            builder('throttle_request')->insert([
                'ip' => $ip,
                'user_agent' => $userAgent,
                'count' => 1,
                'created_at' => now()
            ]);
        }
    }
}
<?php

namespace App\Services\Copilot;

/**
 * Get response from ChatGpt API
 */
class ChatGpt
{
    const API_URL = 'https://api.openai.com/v1/chat/completions';

    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function get()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$this->apiKey}"
        ];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => []
        ];
        $client = new \GuzzleHttp\Client();
        $client->post(self::API_URL, [
            'headers' => $headers,
            'json' => $data
        ]);

    }

}

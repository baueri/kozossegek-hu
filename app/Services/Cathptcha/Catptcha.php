<?php

declare(strict_types=1);

namespace App\Services\Cathptcha;

use Closure;
use Framework\Support\Arr;

class Catptcha
{
    public function questions(): array
    {
        return [
            [
                'question' => 'Hány evangélium van a Szentírásban?',
                'answers' => $this->startsWith(['4', 'négy']),
            ],
            [
                'question' => 'Ki Jézus Krisztus földi helytartója, Róma püspöke?',
                'answers' => $this->contains(['pápa']),
            ],
            [
                'question' => 'Mikor ünnepeljük az Úr Jézus Krisztus kereszthalálát és feltámadását?',
                'answers' => $this->contains(['húsvét']),
            ]
        ];
    }

    protected function contains(array|string|int $contains): Closure
    {
        $contains = Arr::wrap($contains);
        return function (string $answer) use ($contains) {
            foreach ($contains as $contain) {
                if (str_contains($answer, static::sanitize($contain))) {
                    return true;
                }
            }
            return false;
        };
    }

    protected function startsWith(array|string|int $startsWith): Closure
    {
        $startsWith = Arr::wrap($startsWith);
        return function (string $answer) use ($startsWith) {
            foreach ($startsWith as $start) {
                if (str_starts_with($answer, static::sanitize($start))) {
                    return true;
                }
            }
            return false;
        };
    }

    public function getRandomQuestion(): array
    {
        $questions = $this->questions();
        return $questions[array_rand($questions)];
    }

    public function checkAnswer(string $question, string $answer): bool
    {
        $answer = static::sanitize($answer);

        foreach ($this->questions() as $q) {
            if ($q['question'] === $question) {
                return $q['answers']($answer);
            }
        }
        return false;
    }

    protected static function sanitize($string): string
    {
        return mb_strtolower(trim($string));
    }
}
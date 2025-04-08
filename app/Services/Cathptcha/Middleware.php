<?php

declare(strict_types=1);

namespace App\Services\Cathptcha;

class Middleware implements \Framework\Middleware\Middleware
{
    /**
     * @throws Exception
     */
    public function handle(): void
    {
        if (!isset($_SESSION['catptcha_question'])) {
            return;
        }

        $question = $_SESSION['catptcha_question'];
        $answer = request()->get('answer');

        $captcha = new Catptcha();

        $ok = $captcha->checkAnswer($question, $answer);

        if (!$ok) {
            throw new Exception(question: $question, answer: $answer);
        }

        unset($_SESSION['catptcha_question']);
    }
}
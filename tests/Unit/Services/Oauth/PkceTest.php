<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Oauth;

use App\Services\Oauth\Pkce;
use Tests\TestCase;

final class PkceTest extends TestCase
{
    public function testChallengeS256IsDeterministicAndVerifies(): void
    {
        $verifier = str_repeat('a', 43);
        $challenge = Pkce::challengeS256($verifier);

        $this->assertTrue(Pkce::isValidChallenge($challenge));
        $this->assertTrue(Pkce::verifyS256($verifier, $challenge));
        $this->assertFalse(Pkce::verifyS256($verifier . 'x', $challenge));
        $this->assertFalse(Pkce::verifyS256($verifier, $challenge . 'x'));
    }

    public function testRejectsShortVerifierAndChallenge(): void
    {
        $this->assertFalse(Pkce::isValidVerifier('short'));
        $this->assertFalse(Pkce::isValidChallenge('short'));
        $this->assertFalse(Pkce::verifyS256('', 'abc'));
    }
}

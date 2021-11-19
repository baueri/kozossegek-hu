<?php

namespace App\Auth;

interface AuthUser
{
    public function can($right): bool;
}

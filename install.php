<?php

declare(strict_types=1);

include "vendor/autoload.php";

use App\QueryBuilders\Users;
use Framework\Console\In;
use Framework\Console\Out;
use Framework\Support\Password;
use Framework\Support\StringHelper;
use Legacy\UserRole;

Out::info('Telepítés...');
$in = new In();
$adminExists = Users::query()
    ->where('id', 1)
    ->where('user_group', UserRole::SUPER_ADMIN)
    ->exists();
if ($adminExists) {
    Out::info('Admin user már létezik.');
} else {
    Out::info('Admin user létrehozása');
    $name = $in->ask('Neved:', 'Admin');
    $username = $in->ask('Felhasználóneved:', StringHelper::slugify($name, '.'));

    while (!($email = $in->ask('Email címed:'))) {
        Out::warning('Email megadása kötelező');
    }

    $password = $in->ask('Jelszó:', 'pw');

    Users::query()->insert([
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'password' => Password::hash($password),
        'user_group' => UserRole::SUPER_ADMIN,
        'activated_at' => now()
    ]);
}

Out::info('Dummy adatok generálása...');

passthru('composer db:seed -- -s ChurchGroupSeeder');

Out::success('Telepítés kész! Most már be tudsz lépni az admin felületre! ' . route('login'));
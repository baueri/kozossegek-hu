<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\QueryBuilders\Users;
use Framework\Console\Command;
use Framework\Console\Out;
use Framework\Support\Password;
use Framework\Support\StringHelper;
use Legacy\UserRole;

class Install extends Command
{
    public static function signature(): string
    {
        return 'install';
    }

    public function handle(): int
    {
        $this->createAdmin();

        if ($this->getOption('seed') || in_array($this->in->ask('Szeretnél random közösségeket generálni?', 'igen'), ['igen', 'i', 'y'])) {
            $this->seedChurchGroups();
        }

        return 0;
    }

    private function createAdmin(): void
    {
        $adminExists = Users::query()
            ->where('id', 1)
            ->where('user_group', UserRole::SUPER_ADMIN)
            ->exists();

        if ($adminExists) {
            $this->output->info('Admin user már létezik.');
            return;
        }

        $this->output->info('Admin user létrehozása');

        if (!($name = $this->getOption('name'))) {
            $name = $this->in->ask('Neved:', 'Admin');
        }

        if (!($username = $this->getOption('username'))) {
            $username = $this->in->ask('Felhasználóneved:', StringHelper::slugify($name, '.'));
        }

        if (!($email = $this->getOption('email'))) {
            while (!($email = $this->in->ask('Email címed:'))) {
                Out::warning('Email megadása kötelező');
            }
        }

        if (!($password = $this->getOption('password'))) {
            $password = $this->in->ask('Jelszó:', 'pw');
        }

        Users::query()->insert([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Password::hash($password),
            'user_group' => UserRole::SUPER_ADMIN,
            'activated_at' => now()
        ]);
    }

    private function seedChurchGroups(): void
    {
        $this->output->info('Dummy adatok generálása...');
        sleep(2);

        passthru('composer db:seed -- -s ChurchGroupSeeder');

        $this->output->success('Telepítés kész! Most már be tudsz lépni az admin felületre! ' . route('login'));
    }
}
<?php

use Framework\Support\Password;
use Phinx\Seed\AbstractSeed;

class AdminUserSeeder extends AbstractSeed
{

    public function run()
    {
        $basePass = Password::hash('***REMOVED***');
        $users = [
            [
                'name' => 'Bauer Iván',
                'email' => 'birkaivan@gmail.com',
                'username' => 'baueri',
                'password' => Password::hash('cheese90kk')
            ],
            [
                'name' => 'Rónaszéki Benedek',
                'email' => 'ronabene@gmail.com',
                'username' => 'ronabene'
            ],
            [
                'name' => 'Tóth László',
                'email' => 'muse1007@gmail.com',
            ],
            [
                'name' => 'Urbán Ákos',
                'email' => 'akosh.urban@gmail.com'
            ]
        ];

        foreach ($users as $user) {
            $user['password'] = $user['password'] ?? $basePass;
            $this->insert('users', $user);
        }
    }
}

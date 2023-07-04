<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Enums\SocialProvider;
use App\Mail\RegistrationEmail;
use App\QueryBuilders\UserLegalNotices;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserTokens;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Mail\Mailer;
use Framework\Support\Password;
use Google_Client;

class SocialController extends Controller
{
    public function socialLogin(SocialProvider $provider, UserLegalNotices $legalNotices): void
    {
        if ($provider === SocialProvider::google) {

        }
        $credential = $this->request->get('credential');

        $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($credential);
        if ($payload) {
            $socialId = $payload['sub'];
            $user = Users::query()->bySocialLogin(SocialProvider::google, $socialId)->first();

            if (!$user) {
                $user = Users::query()->where('email', $payload['email'])->notDeleted()->first();
                if ($user) {
                    builder('social_profile')->insert([
                        'user_id' => $user->getId(),
                        'social_provider' => SocialProvider::google,
                        'social_id' => $socialId
                    ]);
                }
            }
            if ($user) {
                echo "Sikeres bejelentkezés";
                sleep(1);
                Auth::login($user);
                redirect_route('home');
            }

            $user = Users::query()->create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => Password::generate(16)->hash,
                'activated_at' => now()
            ]);

            Auth::login($user);

            builder('social_profile')->insert([
                'user_id' => $user->getId(),
                'social_provider' => SocialProvider::google,
                'social_id' => $socialId
            ]);

            $token = UserTokens::query()->createActivationToken($user);
            $legalNotices->updateOrInsertCurrentFor($user);
            $message = new RegistrationEmail($user, $token);
            (new Mailer())->to($user->email)->send($message);
            Message::success('Sikeres regisztráció! Az aktiváló linket elküldtük az email címedre.');
            redirect_route('portal.my_profile');
        } else {
            // Invalid ID token
            http_response_code(403);
        }
    }
}

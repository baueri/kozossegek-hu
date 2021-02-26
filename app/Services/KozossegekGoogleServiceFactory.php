<?php

namespace App\Services;

use Google\Exception;
use Google_Client;
use Google_Service_Drive;

class KozossegekGoogleServiceFactory
{
    /**
     * @return Google_Service_Drive
     * @throws Exception
     */
    public function getService()
    {
        return new Google_Service_Drive($this->getClient());
    }

    /**
     * @return Google_Client
     * @throws Exception
     */
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('kozossegek.hu közösség igazolás feltöltés');
        $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->setAuthConfig(ROOT . 'credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $redirect_uri = 'http://local.kozossegek.hu/test';
        $client->setRedirectUri($redirect_uri);
        $a = $client->fetchAccessTokenWithRefreshToken('0AY0e-g4yVGZ_rRri21xfWUqRWcKPG6AJ_Dw255sCjQDuVIHaRB1JYSGuRn7ydlCzMjUjVA');
        dd($a);

        $tokenPath = ROOT . 'google_token.json';

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = '0AY0e-g4yVGZ_rRri21xfWUqRWcKPG6AJ_Dw255sCjQDuVIHaRB1JYSGuRn7ydlCzMjUjVA';

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }
}

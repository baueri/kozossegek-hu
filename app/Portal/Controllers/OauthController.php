<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Services\Oauth\OauthAuthorizationService;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Session;

class OauthController extends PortalController
{
    public function authorize(Request $request, OauthAuthorizationService $oauth): string
    {
        $params = $this->authorizeParams($request);
        $validation = $oauth->validateAuthorizeRequest(
            $params['response_type'],
            $params['client_id'],
            $params['redirect_uri'],
            $params['state'],
            $params['code_challenge'],
            $params['code_challenge_method'],
        );

        if (!$validation['ok']) {
            if ($this->canRedirectWithError($params['redirect_uri'], $oauth->findClient($params['client_id']))) {
                $this->redirectWithQuery($params['redirect_uri'], [
                    'error' => $validation['error'],
                    'error_description' => $validation['description'],
                    'state' => $params['state'] !== '' ? $params['state'] : null,
                ]);
            }

            Message::danger($validation['description']);
            return $this->mintView->render('pages/oauth-error.php', [
                'error' => $validation['error'],
                'description' => $validation['description'],
            ]);
        }

        if (!Auth::loggedIn()) {
            Session::set('last_visited', $_SERVER['REQUEST_URI'] ?? '/oauth/authorize');
            Message::danger('A csatlakozáshoz előbb be kell lépned.');
            redirect_route('login');
        }

        $client = $oauth->findClient($params['client_id']);

        return $this->mintView->render('pages/oauth-consent.php', [
            'client' => $client,
            'params' => $params,
        ]);
    }

    public function decide(Request $request, OauthAuthorizationService $oauth): never
    {
        if (!Auth::loggedIn()) {
            Session::set('last_visited', '/oauth/authorize?' . http_build_query($this->authorizeParams($request)));
            redirect_route('login');
        }

        $params = $this->authorizeParams($request);
        $validation = $oauth->validateAuthorizeRequest(
            $params['response_type'],
            $params['client_id'],
            $params['redirect_uri'],
            $params['state'],
            $params['code_challenge'],
            $params['code_challenge_method'],
        );

        if (!$validation['ok']) {
            $this->redirectWithQuery($params['redirect_uri'], [
                'error' => $validation['error'],
                'error_description' => $validation['description'],
                'state' => $params['state'] !== '' ? $params['state'] : null,
            ]);
        }

        $client = $oauth->findClient($params['client_id']);
        $decision = (string) $request->get('decision', '');

        if ($decision !== 'allow') {
            $this->redirectWithQuery($params['redirect_uri'], [
                'error' => 'access_denied',
                'state' => $params['state'],
            ]);
        }

        $code = $oauth->createAuthorizationCode(
            $client,
            Auth::user(),
            $params['redirect_uri'],
            $params['code_challenge'],
            $params['code_challenge_method'],
        );

        $this->redirectWithQuery($params['redirect_uri'], [
            'code' => $code,
            'state' => $params['state'],
        ]);
    }

    public function token(Request $request, OauthAuthorizationService $oauth): array
    {
        Response::asJson();

        $result = $oauth->exchangeAuthorizationCode(
            (string) $request->get('grant_type', ''),
            (string) $request->get('code', ''),
            (string) $request->get('redirect_uri', ''),
            (string) $request->get('client_id', ''),
            (string) $request->get('code_verifier', ''),
        );

        if (isset($result['error'])) {
            Response::setStatusCode(400);

            return $result;
        }

        return $result;
    }

    public function revoke(Request $request, OauthAuthorizationService $oauth): array
    {
        Response::asJson();

        $plain = trim((string) $request->get('token', ''));
        if ($plain === '') {
            $header = (string) ($request->getHeader('AUTHORIZATION')
                ?? $_SERVER['HTTP_AUTHORIZATION']
                ?? '');
            if (preg_match('/^Bearer\s+(\S+)$/i', trim($header), $matches)) {
                $plain = $matches[1];
            }
        }

        if ($plain === '') {
            Response::setStatusCode(400);

            return [
                'success' => false,
                'message' => 'token is required',
            ];
        }

        $oauth->revokePlainToken($plain);

        // RFC 7009: always succeed to avoid token probing
        return ['success' => true];
    }

    /**
     * @return array{
     *   response_type: string,
     *   client_id: string,
     *   redirect_uri: string,
     *   state: string,
     *   code_challenge: string,
     *   code_challenge_method: string
     * }
     */
    private function authorizeParams(Request $request): array
    {
        return [
            'response_type' => (string) $request->get('response_type', ''),
            'client_id' => (string) $request->get('client_id', ''),
            'redirect_uri' => (string) $request->get('redirect_uri', ''),
            'state' => (string) $request->get('state', ''),
            'code_challenge' => (string) $request->get('code_challenge', ''),
            'code_challenge_method' => (string) $request->get('code_challenge_method', 'S256'),
        ];
    }

    private function canRedirectWithError(string $redirectUri, $client): bool
    {
        if (!$client) {
            return false;
        }

        $parts = parse_url($redirectUri);

        return is_array($parts)
            && strtolower((string) ($parts['scheme'] ?? '')) === 'https'
            && !empty($parts['host']);
    }

    /**
     * @param array<string, string|null> $query
     */
    private function redirectWithQuery(string $redirectUri, array $query): never
    {
        $filtered = [];
        foreach ($query as $key => $value) {
            if ($value !== null && $value !== '') {
                $filtered[$key] = $value;
            }
        }

        $separator = str_contains($redirectUri, '?') ? '&' : '?';
        redirect($redirectUri . $separator . http_build_query($filtered));
    }
}

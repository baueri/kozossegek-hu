<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\HoneypotException;
use App\Services\ReplayAttackProtection\Exception as ReplayAttackException;
use Error;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Http\Exception\TokenMismatchException;
use Framework\Http\Response;
use Framework\Model\Exceptions\ModelNotFoundException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandler
{
    /**
     * @throws HoneypotException
     * @throws TokenMismatchException
     */
    public function __invoke($error): void
    {
        if ($error->getCode() != '404' && !env('DEBUG')) {
            if ($error instanceof TokenMismatchException) {
                log_event('csrf_fail', ['request' => request()->all()]);
                abort(403);
            } elseif ($error instanceof HoneypotException) {
                log_event('honeypot_fail', ['request' => request()->all(), 'reason' => $error->reason]);
                abort(403);
            }  elseif ($error instanceof \App\Services\Cathptcha\Exception) {
                log_event('catpcha_fail', ['request' => request()->all(), 'q,a' => $error->question . ',' . $error->answer]);
                abort(403);
            } elseif ($error instanceof ReplayAttackException) {
                log_event('replay_attack', ['request' => request()->all()]);
                abort(403);
            } else {
                report($error);
            }
        }

        Response::setStatusCode($error->getCode() ?: 500);

        if (Response::contentTypeIsJson()) {
            $response = [
                'success' => false,
            ];

            if (env('DEBUG')) {
                $response['message'] = $error->getMessage();
                $response['trace'] = explode("\n", $error->getTraceAsString());
            }

            print json_encode($response);
            return;
        }

        if (config('app.debug') && $error->getCode() != '401') {
            $whoops = new Run;
            $whoops->allowQuit(true);
            $whoops->pushHandler(new PrettyPageHandler);
            $whoops->handleException($error);
//            echo "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($error) . ")</h3>";
//            echo "{$error->getMessage()} in <b>{$error->getFile()}</b> on line <b>{$error->getLine()}</b> \n\n";
//            echo var_export($error->getTrace(), true);
//            echo "</pre>";
            exit;
        }

        try {
            throw $error;
        } catch (PageNotFoundException | ModelNotFoundException | RouteNotFoundException $error) {
            print(view('portal.error', [
                'code' => $error->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch (UnauthorizedException $error) {
            print(view('portal.error', [
                'code' => $error->getCode(),
                'message2' => 'Nincs jogosultsága az oldal megtekintéséhez']));
        } catch (Error | Exception $error) {
            error_log($error->getMessage() . "\n" . $error->getTraceAsString());

            print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}

<?php

namespace Framework\Http;

use Error;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Kernel;
use Framework\Middleware\Middleware;
use Framework\Model\Exceptions\ModelNotFoundException;
use Throwable;

abstract class HttpKernel implements Kernel
{
    /**
     * @var class-string<Middleware>[]
     */
    protected array $middleware = [];

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function handleMaintenance()
    {
        echo '<h1>Website under maintenance</h1>';
    }

    /**
     * @throws \Throwable
     * @var Error|Throwable|Exception $exception
     */
    public function handleError($exception)
    {
        Response::setStatusCode($exception->getCode() ?: 500);

        if (Response::contentTypeIsJson()) {
            print json_encode([
                'success' => false,
                'error_code' => $exception->getCode()
            ]);
            throw $exception;
        }

        if (config('app.debug') && $exception->getCode() != '401') {
            echo "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($exception) . ")</h3>";
            echo "{$exception->getMessage()} in <b>{$exception->getFile()}</b> on line <b>{$exception->getLine()}</b> \n\n";
            echo $exception->getTraceAsString();
            echo "</pre>";
            exit;
        }

        try {
            throw $exception;
        } catch (PageNotFoundException | ModelNotFoundException | RouteNotFoundException $exception) {
            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch (UnauthorizedException $exception) {
            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message2' => 'Nincs jogosultsága az oldal megtekintéséhez']));
        } catch (Error | Exception $exception) {
            error_log($exception);

            return print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}

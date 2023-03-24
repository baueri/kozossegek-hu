<?php

namespace Framework\Http;

use Error;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Exception\HttpException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Kernel;
use Framework\Middleware\Middleware;
use Framework\Model\Exceptions\ModelNotFoundException;
use Throwable;

class HttpKernel implements Kernel
{
    /**
     * @var string[]|Middleware[]
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
     * @throws Throwable
     * @var Error|Throwable|Exception $error
     */
    public function handleError($error)
    {
        Response::setStatusCode(ResponseStatus::isValidResponse($error->getCode()) ? $error->getCode() : 500);

        if (Response::contentTypeIsJson() || request()->wantsJson()) {
            if (app()->debug()) {
                throw $error;
            }
            elseif ($error instanceof HttpException) {
                $message = $error->getMessage();
            } else {
                $message = 'Server error';
            }
            print json_encode(compact('message'));
            exit;
        }

        if (app()->debug() && $error->getCode() != '401') {
            echo "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($error) . ")</h3>";
            echo "{$error->getMessage()} in <b>{$error->getFile()}</b> on line <b>{$error->getLine()}</b> \n\n";
            echo $error->getTraceAsString();
            echo "</pre>";
            exit;
        }

        try {
            throw $error;
        } catch (PageNotFoundException | ModelNotFoundException | RouteNotFoundException $error) {
            return print(view('portal.error', [
                'code' => $error->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch (UnauthorizedException $error) {
            return print(view('portal.error', [
                'code' => $error->getCode(),
                'message2' => 'Nincs jogosultsága az oldal megtekintéséhez']));
        } catch (Error | Exception $error) {
            error_log($error);

            return print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}

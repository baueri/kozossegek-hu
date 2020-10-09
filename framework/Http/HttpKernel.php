<?php


namespace Framework\Http;


use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Exception\RouteNotFoundException;
use Framework\Model\ModelNotFoundException;
use Framework\Middleware\Middleware;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Kernel;

class HttpKernel implements Kernel
{
    /**
     * @var string[]|Middleware[]
     */
    protected $middleware = [];

    public function getMiddleware()
    {
        return $this->middleware;
    }

    public function handleMaintenance()
    {
        echo '<h1>Website under maintenance</h1>';
    }

   /**
    * @var \Error|\Throwable|\Exception $exception
    */
    public function handleError($exception)
    {
        http_response_code((int) $exception->getCode() ?: 500);

        if (Response::contentTypeIsJson()) {
            print json_encode([
                'success' => false,
                'error_code' => $exception->getCode()
            ]);
            throw $exception;
        }


        if (config('app.debug')) {
            echo "<pre style='white-space:pre-line'><h3>Unexpected error (" . get_class($exception) . ")</h3>";
            echo "{$exception->getMessage()} in <b>{$exception->getFile()}</b> on line <b>{$exception->getLine()}</b> \n\n";
            echo $exception->getTraceAsString();
            echo "</pre>";
            exit;
        }

        try {
            throw $exception;
        } catch (PageNotFoundException $exception) {
            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message' => 'A keresett oldal nem található',
                'message2' => 'Az oldal, amit keresel lehet, hogy törölve lett vagy ideiglenesen nem elérhető.']));
        } catch(UnauthorizedException $exception) {

            error_log($exception);

            return print(view('portal.error', [
                'code' => $exception->getCode(),
                'message2' => 'Nincs jogosultsága az oldal megtekintéséhez']));
        } catch (\Error|\Exception $exception) {

            error_log($exception);

            return print(view('portal.error', [
                'code' => 500,
                'message' => 'Váratlan hiba történt',
                'message2' => 'Az oldal üzemeltetői értesítve lettek a hibáról'
            ]));
        }
    }
}

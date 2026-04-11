<?php

declare(strict_types=1);

use App\Auth\Auth;
use App\Http\ErrorHandler;
use App\Middleware\DebugBarMiddleware;
use App\Providers\AppServiceProvider;
use Baueri\Mint\View as MintView;
use Framework\Http\HttpKernel;
use Framework\Http\Session;
use Framework\Http\View\View;
use Framework\Middleware\AuthMiddleware;
use Framework\Middleware\BaseAuthMiddleware;
use Framework\Middleware\Translation;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

if (file_exists('../.maintenance')) {
    include '../resources/views/maintenance.php';
    exit;
}

include '../vendor/autoload.php';

Session::start();

ob_start();

$app = app();

try {
    $app->bind('errorHandler', ErrorHandler::class, true);

    $kernel = $app->get(HttpKernel::class);

    $kernel->middleware(function() {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    })->middleware(BaseAuthMiddleware::class)
        ->middleware(DebugBarMiddleware::class)
        ->middleware(Translation::class)
        ->middleware(AuthMiddleware::class)
        ->middleware(AppServiceProvider::class)
        ->middleware(function (MintView $mint) {
            // Merged in every MintView::render() (layouts, includes, component templates). Per-render
            // and explicit component props override the same keys (see vendor MintView::render).
            $mint->share([
                'display_events' => (bool) env('DISPLAY_EVENTS'),
                'display_news' => (bool) env('DISPLAY_NEWS'),
                'is_prod' => is_prod(),
                'is_home' => is_home(),
                'contact_email' => (string) config('app.contact_email'),
                'is_logged_in' => Auth::loggedIn(),
                'is_admin' => Auth::user()?->isAdmin() ?? false,
                'social_enabled' => social_provider_enabled(),
                'cookie_show_ga' => is_prod(),
                'cookie_show_fb' => is_prod() && (bool) env('FACEBOOK_APP_ID'),
                'cookie_fb_app_id' => (string) env('FACEBOOK_APP_ID', ''),
            ]);
        });

    View::setVariable('captchaEnabled', (bool) config('app.captcha_enabled'));
    View::setVariable('contact_email', config('app.contact_email'));

    $kernel->handle();
} catch (Error | Exception | Throwable $e) {
    ob_get_clean();
    $app->handleError($e);
}

<?php

declare(strict_types=1);

namespace App\View\Mint;

use Baueri\Mint\Cache;
use Baueri\Mint\MintCompiler;
use Baueri\Mint\MintView;

final class MintFactory
{
    public static function create(string $viewsPath, string $cachePath): MintView
    {
        $compiler = new MintCompiler($viewsPath);

        $compiler->registerViewComponent('icon', 'components/icon.php');
        $compiler->registerViewComponent('event-card', 'components/event-card.php');
        $compiler->registerViewComponent('kozosseg-card', 'components/kozosseg-card.php');
        $compiler->registerViewComponent('og-image', 'components/og-image.php');
        $compiler->registerViewComponent('search-box', 'partials/search_box.php');
        $compiler->registerViewComponent('google-login', 'partials/google_login.php');
        $compiler->registerViewComponent('cookie-consent', 'partials/cookie_consent.php');

        $compiler->registerTextDirective(new RouteTextDirective());
        $compiler->registerTextDirective(new LangTextDirective());
        $compiler->registerTextDirective(new ActiveLinkClassTextDirective());
        $compiler->registerTextDirective(new CsrfTextDirective());
        $compiler->registerTextDirective(new LazySrcTextDirective());

        return new MintView($viewsPath, new Cache($cachePath), $compiler);
    }
}

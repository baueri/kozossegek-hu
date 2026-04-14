<?php

declare(strict_types=1);

namespace App\View\Mint;

use App\View\Mint\Components\Captcha;
use App\View\Mint\Components\EventCard;
use App\View\Mint\Components\HoneypotField;
use App\View\Mint\Components\Icon;
use App\View\Mint\Components\KozossegCard;
use App\View\Mint\Components\Modal;
use App\View\Mint\Components\Pager;
use App\View\Mint\Components\ReplayAttack;
use Baueri\Mint\Cache;
use Baueri\Mint\MintCompiler;
use Baueri\Mint\MintView;

final class MintFactory
{
    public static function create(string $viewsPath, string $cachePath): MintView
    {
        $compiler = new MintCompiler($viewsPath);

        $compiler->registerModule('icon', Icon::class);
        $compiler->registerModule('event-card', EventCard::class);
        $compiler->registerModule('kozosseg-card', KozossegCard::class);
        $compiler->registerModule('honeypot', HoneypotField::class);
        $compiler->registerModule('replay-attack', ReplayAttack::class);
        $compiler->registerModule('captcha', Captcha::class);
        $compiler->registerModule('pager', Pager::class);
        $compiler->registerModule('modal', Modal::class);
        $compiler->registerViewModule('og-image', 'components/og-image.php');
        $compiler->registerViewModule('search-box', 'partials/search_box.php');
        $compiler->registerViewModule('google-login', 'partials/google_login.php');
        $compiler->registerViewModule('cookie-consent', 'partials/cookie_consent.php');
        $compiler->registerViewModule('group-contact-form', 'partials/group-contact-form.php');
        $compiler->registerViewModule('alert', 'components/alert.php');

        $compiler->registerTextDirective(new RouteTextDirective());
        $compiler->registerTextDirective(new LangTextDirective());
        $compiler->registerTextDirective(new ActiveLinkClassTextDirective());
        $compiler->registerTextDirective(new CsrfTextDirective());
        $compiler->registerTextDirective(new LazySrcTextDirective());
        $compiler->registerTextDirective(new AdminTextDirective());

        $view = new MintView($viewsPath, new Cache($cachePath), $compiler);

        $view->registerNamespace('legacy', VIEWS);

        return $view;
    }
}

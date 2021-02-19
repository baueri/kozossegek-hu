<?php

use App\Helpers\HoneyPot;
use App\Http\Components\FacebookShareButton;
use App\Http\Components\FeaturedTitle;
use App\Http\Components\FontawesomeIcon;
use App\Http\Components\HoneyPotComponent;
use App\Http\Components\Selectors\AgeGroupSelector;
use App\Http\Components\Selectors\JoinModeSelector;
use App\Http\Components\Selectors\OccasionFrequencySelector;
use App\Http\Components\Selectors\OnDaysSelector;
use App\Http\Components\Selectors\SpiritualMovementSelector;
use Framework\Http\View\View;

return [
    'view_sources' => [
        'email_templates' => _env('STORAGE_PATH') . 'email_templates'
    ],
    'directives' => [
        'header' => function ($matches) {
            if (strpos($matches[0], '@endheader') !== false) {
                return '<?php }); ?>';
            }

            return '<?php $__env->getSection()->add("header", function($args) { extract($args); ?> ';
        },
        'footer' => function ($matches) {
            if (strpos($matches[0], '@endfooter') !== false) {
                return '<?php }); ?>';
            }

            return '<?php $__env->getSection()->add("footer", function($args) { extract($args); ?> ';
        },
        'featuredTitle' =>  function ($matches) {
            return View::component(FeaturedTitle::class, $matches[1]);
        },
        'age_group_selector' => function ($matches) {
            return View::component(AgeGroupSelector::class, $matches[1]);
        },
        'occasion_frequency_selector' => function ($matches) {
            return View::component(OccasionFrequencySelector::class, $matches[1]);
        },
        'on_days_selector' => function ($matches) {
            return View::component(OnDaysSelector::class, $matches[1]);
        },
        'spiritual_movement_selector' => function ($matches) {
            return View::component(SpiritualMovementSelector::class, $matches[1]);
        },
        'join_mode_selector' => function ($matches) {
            return View::component(JoinModeSelector::class, $matches[1]);
        },
        'facebook_share_button' => fn($matches) => View::component(FacebookShareButton::class, $matches[1]),
        'alert' => function ($matches) {

            if (strpos($matches[0], '@alert') !== false) {
                return '<div class="alert alert-' . str_replace(['\'', '"'], '', $matches[1]) . '">';
            }

            return '</div>';
        },
        'admin' => function ($matches) {

            if ($matches[0] == '@endadmin') {
                return '<?php endif; ?>';
            }

            return '<?php if(\App\Auth\Auth::user()->isAdmin()): ?>';
        },
        'upload' => function ($matches) {
            $file = str_replace("'", "", $matches[1]);
            return "/storage/uploads/$file";
        },
        'message' => function ($matches) {
            return "<?php echo view('admin.partials.message') ?>";
        },
        'honeypot' => function () {
            return View::component(HoneyPotComponent::class);
        },
        'icon' => fn($matches) => View::component(FontawesomeIcon::class, $matches[1]),
    ]
];

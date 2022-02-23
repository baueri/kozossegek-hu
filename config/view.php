<?php

use App\Http\Components\AszfCheckBox;
use App\Http\Components\ComponentParser;
use App\Http\Components\FacebookShareButton;
use App\Http\Components\FeaturedTitle;
use App\Http\Components\FontawesomeIcon;
use App\Http\Components\HoneyPotComponent;
use App\Http\Components\Selectors\AgeGroupSelector;
use App\Http\Components\Selectors\JoinModeSelector;
use App\Http\Components\Selectors\OccasionFrequencySelector;
use App\Http\Components\Selectors\OnDaysSelector;
use App\Http\Components\Selectors\SpiritualMovementSelector;
use App\Http\Components\Selectors\UserGroupSelector;

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
            if (str_contains($matches[0], '@endfooter')) {
                return '<?php }); ?>';
            }

            return '<?php $__env->getSection()->add("footer", function($args) { extract($args); ?> ';
        },
        'featuredTitle' => FeaturedTitle::class,
        'spiritual_movement_selector' => SpiritualMovementSelector::class,
        'join_mode_selector' => JoinModeSelector::class,
        'user_group_selector' => UserGroupSelector::class,
        'facebook_share_button' => FacebookShareButton::class,
        'alert' => function ($matches) {
            if (str_contains($matches[0], '@alert')) {
                return '<div class="alert alert-' . str_replace(['\'', '"'], '', $matches[1]) . ' shadow-sm">';
            }

            return '</div>';
        },
        'admin' => function ($matches) {
            if ($matches[0] == '@endadmin') {
                return '<?php endif; ?>';
            }

            return '<?php if(\App\Auth\Auth::loggedIn() && \App\Auth\Auth::user()->isAdmin()): ?>';
        },
        'upload' => function ($matches) {
            $file = str_replace("'", "", $matches[1]);
            return "/storage/uploads/$file";
        },
        'message' => fn () => "<?php echo view('admin.partials.message') ?>",
        'honeypot' => HoneyPotComponent::class,
        'icon' => FontawesomeIcon::class,
        'filter_box' => function ($matches) {
            if (str_contains($matches[0], '@endfilter_box')) {
                return '</div>';
            }

            return '<div class="shadow-sm bg-white p-3 rounded"><h5>Szűrő</h5>';
        },
        'component' => ComponentParser::class,
        'selected' => fn ($matches) => "<?php if($matches[1]): echo 'selected'; endif; ?>",
        'dump' => fn($matches) => "<?php d($matches[1]); ?>"
    ],
    'components' => [
        'aszf' => AszfCheckBox::class,
        'day_selector' => OnDaysSelector::class,
        'age_group_selector' => AgeGroupSelector::class,
        'occasion_frequency_selector' => OccasionFrequencySelector::class
    ]
];

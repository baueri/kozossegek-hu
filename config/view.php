<?php

return [
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
            return "<?php echo (new \App\Http\Components\FeaturedTitle)->render($matches[1]) ?>";
        },
        'age_group_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Components\Selectors\AgeGroupSelector)->render($matches[1]) ?>";
        },
        'occasion_frequency_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Components\Selectors\OccasionFrequencySelector)->render($matches[1]) ?>";
        },
        'on_days_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Components\Selectors\OnDaysSelector)->render($matches[1]) ?>";
        },
        'spiritual_movement_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Components\Selectors\SpiritualMovementSelector)->render($matches[1]) ?>";
        },
        'alert' => function ($matches) {

            if (strpos($matches[0], '@alert') !== false) {
                return '<div class="alert alert-' . str_replace(['\'', '"'], '', $matches[1]) . '">';
            }

            return '</div>';
        },
        'upload' => function ($matches) {
            $file = str_replace("'", "", $matches[1]);
            return "/storage/uploads/$file";
        },
        'message' => function ($matches) {
            return "<?php echo view('admin.partials.message') ?>";
        }
    ]
];

<?php

return [
    'directives' => [
        'header' => function($matches) {
            if (strpos($matches[0], '@endheader') !== false) {
                return '<?php }); ?>';
            }

            return '<?php $__env->getSection()->add("header", function($args) { extract($args); ?> ';
        },
        'featuredTitle' =>  function($matches){
            return "<?php echo view('portal.partials.featured-title', ['title' => {$matches[1]}]); ?>";
        },
        'age_group_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Selectors\AgeGroupSelector)->render($matches[1]) ?>";
        },
        'occasion_frequency_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Selectors\OccasionFrequencySelector)->render($matches[1]) ?>";
        },
        'on_days_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Selectors\OnDaysSelector)->render($matches[1]) ?>";
        },
        'spiritual_movement_selector' => function ($matches) {
            return "<?php echo (new \App\Http\Selectors\SpiritualMovementSelector)->render($matches[1]) ?>";
        }
    ]
];
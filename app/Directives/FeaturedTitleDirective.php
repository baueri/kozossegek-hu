<?php

declare(strict_types=1);

namespace App\Directives;

use Framework\Http\View\Directives\AtDirective;

class FeaturedTitleDirective extends AtDirective
{
    public function getName(): string
    {
        return 'featuredTitle';
    }

    public function getReplacement(array $matches): string
    {
        if (isset($matches[1])) {
            $content = '<?php echo ' . $matches[1] . '; ?>';
            return <<<HTML
            <div class="featured-header py-4" style="background-color: #1B3E4A;">
                <div class="container-fluid justify-content-center">
                    <div class="featured-content"><h1 class="py-3">$content</h1></div>
                </div>
            </div>
            HTML;

        }
        if ($matches[0] !== '@endfeaturedTitle') {
            return <<<HTML
                <div class="featured-header py-4" style="background-color: #1B3E4A;">
                    <div class="container-fluid justify-content-center">
                        <div class="featured-content">
            HTML;
        }

        return <<<HTML
                    </div>
                </div>
            </div>
        HTML;
    }
}

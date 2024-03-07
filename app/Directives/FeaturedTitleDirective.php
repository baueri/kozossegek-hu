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
            <div class="featured-header py-3 bg-main"">
                <div class="container justify-content-center">
                    <div class="featured-content"><h3 class="py-3 mb-0">$content</h3></div>
                </div>
            </div>
            HTML;

        }
        if ($matches[0] !== '@endfeaturedTitle') {
            return <<<HTML
                <div class="featured-header py-3 bg-main"">
                    <div class="container justify-content-center">
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

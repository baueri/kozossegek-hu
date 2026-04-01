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
            <section class="page-header">
                <div class="container">
                    <h1 class="page-title">{$content}</h1>
                </div>
            </section>
            HTML;

        }
        if ($matches[0] !== '@endfeaturedTitle') {
            return <<<HTML
                <section class="page-header">
                    <div class="container">
            HTML;
        }

        return <<<HTML
                </div>
            </section>
        HTML;
    }
}

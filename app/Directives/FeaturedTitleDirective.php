<?php

declare(strict_types=1);

namespace App\Directives;

use Framework\Http\View\Directives\AtDirective;

class FeaturedTitleDirective extends AtDirective
{
    /**
     * Same balanced-paren rule as {@see \Framework\Http\View\Directives\IfDirective}
     * so titles like @featuredTitle($event->exists() ? 'A' : 'B') compile correctly.
     */
    private const BALANCED_EXPR = '(?:[^()]|\((?:[^()]++|(?1))*\))*+';

    public function getName(): string
    {
        return 'featuredTitle';
    }

    public function getPattern(): string
    {
        $e = self::BALANCED_EXPR;

        return '/@featuredTitle\(\s*(' . $e . ')?\s*\)|@endfeaturedTitle/';
    }

    public function getReplacement(array $matches): string
    {
        if ($matches[0] === '@endfeaturedTitle') {
            return <<<HTML
                </div>
            </section>
        HTML;
        }

        $expr = isset($matches[1]) ? trim((string) $matches[1]) : '';

        if ($expr !== '') {
            $content = '<?php echo ' . $expr . '; ?>';

            return <<<HTML
            <section class="page-header">
                <div class="container">
                    <h1 class="page-title">{$content}</h1>
                </div>
            </section>
            HTML;
        }

        return <<<HTML
                <section class="page-header">
                    <div class="container">
            HTML;
    }
}

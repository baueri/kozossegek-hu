<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

class ForeachDirective extends AtDirective
{
    /**
     * Balanced parenthesis expression for the full PHP foreach (...) clause.
     * (?1) recurses to the first capture group — same approach as {@see IfDirective}.
     */
    private const BALANCED_EXPR = '(?:[^()]|\((?:[^()]++|(?1))*\))*+';

    public function getName(): string
    {
        return 'foreach';
    }

    public function getPattern(): string
    {
        $e = self::BALANCED_EXPR;

        return '/@foreach\(\s*(' . $e . ')\s*\)|@endforeach/';
    }

    public function getReplacement(array $matches): string
    {
        if ($matches[0] === '@endforeach') {
            return '<?php endforeach; ?>';
        }

        return '<?php foreach(' . $matches[1] . '): ?>';
    }
}

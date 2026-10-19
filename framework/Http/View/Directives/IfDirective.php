<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

class IfDirective implements Directive
{
    /**
     * Balanced parenthesis expression for @if / @elseif conditions.
     * (?1) recurses to the first capture group (same subpattern), so calls like $obj->method() work.
     */
    private const BALANCED_EXPR = '(?:[^()]|\((?:[^()]++|(?1))*\))*+';

    public function getName(): string
    {
        return 'if';
    }

    public function getReplacement(array $matches): string
    {
        if (str_starts_with($matches[0], '@end')) {
            return '<?php endif; ?>';
        }

        if (str_starts_with($matches[0], '@elseif')) {
            return '<?php elseif(' . $matches[2] . '): ?>';
        }

        if (str_starts_with($matches[0], '@else')) {
            return '<?php else: ?>';
        }

        return '<?php if(' . $matches[1] . '): ?>';
    }

    public function getPattern(): string
    {
        $e = self::BALANCED_EXPR;

        return '/@if\(\s*(' . $e . ')\s*\)|@elseif\(\s*(' . $e . ')\s*\)|(@else\b)|(@endif)/';
    }
}

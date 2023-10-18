<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

class ComponentExpression implements Directive
{
    public function getPattern(): string
    {
        return '/<(\w+)(?=[^>]*\s(x:(foreach|if|for)=("[^"]*")?))[^>]*>(.*?)<\/\1>|<(\w+)(?=[^>]*\sx:(foreach|if|for)(="[^"]*")?)[^>]*\/>/is';
    }

    public function getReplacement(array $matches): string
    {
        [$match, $tag, $origPart, $expressionType, $expression, $content] = $matches;
        return "<?php $expressionType ($expression):?>" . PHP_EOL . str_replace($origPart, '', $match) . PHP_EOL . "<?php end{$expressionType};?>";
    }
}

// pattern between two double quotes: (?<=")[^"]+(?=")
<?php

declare(strict_types=1);

namespace Framework\Http\View\Directives;

class ComponentExpression implements Directive
{
    public function getPattern(): string
    {
        return '/<([^>]+)(?<part>x:(?<expressionType>if|for|foreach)="(?<expression>[^"]*)").*(?:\/?)>(?:(.*?)<\/\1>)?/s';
    }

    public function getReplacement(array $matches): string
    {
        dd('todo');
        array_walk($matches, fn ($item) =>htmlspecialchars($item));
        dd($matches[0], $matches[1], $matches[2], $matches[3], $matches[4]);

        [0 => $match, 'part' => $origPart, 'expressionType' => $expressionType, 'expression' => $expression] = $matches;
        echo "<pre>" . htmlspecialchars($match);exit;
        return "<?php $expressionType ($expression):?>" . PHP_EOL . str_replace($origPart, '', $match) . PHP_EOL . "<?php end{$expressionType};?>";
    }
}

// pattern between two double quotes: (?<=")[^"]+(?=")
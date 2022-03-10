<?php

namespace Framework\Http\View\Directives;

class IfDirective implements Directive
{

    public function getName()
    {
        return 'if';
    }

    public function getReplacement(array $matches): string
    {
        if (str_starts_with($matches[0], '@end')) {
            return '<?php endif; ?>';
        }

        if (strpos($matches[0], '@elseif') === 0) {
            return '<?php elseif(' . $matches[2] . '): ?>';
        }

        if (strpos($matches[0], '@else') === 0) {
            return '<?php else: ?>';
        }

        return '<?php if(' . $matches[1] . '): ?>';
    }

    public function getPattern(): string
    {
        return '/@if\((.*)\)|@elseif\((.*)\)|(@else)|(@endif)/';
    }
}
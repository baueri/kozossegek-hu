<?php


namespace Framework\Http\View\Directives;


class IfDirective implements Directive
{

    public function getName()
    {
        return 'if';
    }

    /**
     * @param array $matches
     * @return string
     */
    public function getReplacement(array $matches)
    {
        if (strpos($matches[0], '@end') === 0) {
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

    /**
     * @return string
     */
    public function getPattern()
    {
        return '/@if\((.*)\)|@elseif\((.*)\)|(@else)|(@endif)/';
    }
}
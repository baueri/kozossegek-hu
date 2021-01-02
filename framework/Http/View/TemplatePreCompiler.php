<?php

namespace Framework\Http\View;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

class TemplatePreCompiler
{

    private $expressions = [
        'for', 'foreach', 'if', 'else', 'else-if'
    ];

    private $closableExpressions = [
        'for', 'foreach'
    ];

    public function parse($content)
    {
        $dom = new DOMDocument();

        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);

        $currentNode = $dom->firstChild;

        /* @var $currentNode DOMElement */
        while ($currentNode = $this->next($currentNode)) {
            if (!$currentNode instanceof DOMElement) {
                continue;
            }
            foreach ($this->expressions as $expression) {
                $attributeName = "x:$expression";

                if ($attribute = $currentNode->getAttribute($attributeName)) {
                    $currentNode->parentNode->insertBefore(new DOMText("<?php {$expression}({$attribute}): ?>"), $currentNode);
                    $currentNode->removeAttribute($attributeName);

                    if ($currentNode->hasAttribute('x:if') && ($currentNode->nextSibling->hasAttribute('x:else') || $currentNode->nextSibling->hasAttribute('x:else-if'))) {
                        continue;
                    }

                    if (($currentNode->hasAttribute('x:else') || $currentNode->hasAttribute('x:else-if'))) {
                        if (!$currentNode->nextSibling->hasAttribute('x:else') && !$currentNode->nextSibling->hasAttribute('x:else-if')) {
                            if ($currentNode->nextSibling === null) {
                                $currentNode->parentNode->appendChild(new DOMText("<?php endif; ?>"));
                            } else {
                                $currentNode->parentNode->insertBefore(new DOMText("<?php endif; ?>"), $currentNode->nextSibling);
                            }
                        }
                    } else {
                        if ($currentNode->nextSibling === null) {
                            $currentNode->parentNode->appendChild(new DOMText("<?php end{$expression}; ?>"));
                        } else {
                            $currentNode->parentNode->insertBefore(new DOMText("<?php end{$expression}; ?>"), $currentNode->nextSibling);
                        }
                    }
                }
            }
        }

        $html = htmlspecialchars_decode($dom->saveHTML($dom->documentElement));

        $html = preg_replace(["/<\\/?html(.|\\s)*?>/", "/<\\/?body(.|\\s)*?>/"], "", $html);

        return $html;
    }

    private function next(DOMNode $domElement): ?DOMElement
    {
        if ($domElement->hasChildNodes()) {
            foreach ($domElement->childNodes as $childNode) {
                if ($childNode instanceof DOMElement) {
                    return $childNode;
                }
            }
        }

        if ($domElement->nextSibling && $domElement->nextSibling instanceof DOMElement) {
            return $domElement->nextSibling;
        }

        return $domElement->parentNode->nextSibling instanceof DOMElement ? $domElement->parentNode->nextSibling : null;
    }
}

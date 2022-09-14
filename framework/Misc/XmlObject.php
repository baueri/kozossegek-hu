<?php

namespace Framework\Misc;

use SimpleXMLElement;

class XmlObject extends SimpleXMLElement
{
    /**
     * @return array
     */
    public function getParentsWithCurrentNode()
    {
        return array_merge($this->getParents(), [$this]);
    }

    /**
     * @return array
     */
    public function getParents()
    {
        $parents = [];

        $node = $this->getParentNode();

        $parents[] = $node;

        while ($node = $node->getParentNode()) {
            array_unshift($parents, $node);
        }

        return $parents;
    }

    /**
     * @return mixed
     */
    public function getParentNode()
    {
        return current($this->xpath('parent::*'));
    }
}
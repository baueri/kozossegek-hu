<?php

namespace Framework\Model\Relation;

enum Has implements RelationType
{
    case many;
    case one;
}

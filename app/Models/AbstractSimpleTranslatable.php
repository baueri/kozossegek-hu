<?php

namespace App\Models;

/**
 * Description of AbstractSimpleTranslatable
 *
 * @author ivan
 */
abstract class AbstractSimpleTranslatable {
    
    public $name;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function translate()
    {
        $className = substr(static::class, strrpos(static::class, '\\')+1);
        
        return lang(\Framework\Support\StringHelper::snake($className) . '.' . $this->name);
    }
    
    public function __toString() {
        return $this->translate();
    }
}

<?php

namespace Framework;

interface Kernel
{
    public function handle();
    
    public function handleError($error);
}

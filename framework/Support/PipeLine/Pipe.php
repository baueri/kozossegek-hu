<?php


namespace Framework\Support\PipeLine;


use Framework\Support\LinkedList;

abstract class Pipe extends LinkedList
{
    abstract public function handle($data);

    public function next($data)
    {
        /* @var $pipe Pipe */
        $pipe = $this->next;

        if ($pipe) {
            return $pipe->handle($data);
        }
    }

}
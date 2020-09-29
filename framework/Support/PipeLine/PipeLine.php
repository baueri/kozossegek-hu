<?php


namespace Framework\Support\PipeLine;


use Framework\Support\LinkedList;

class PipeLine
{
    /**
     * @var Pipe
     */
    protected $pipe;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param mixed $data
     */
    public function send($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param Pipe[]|string[] $pipes
     * @return PipeLine
     */
    public function pipes(array $pipes)
    {
        $firstPipe = null;

        foreach (array_reverse($pipes) as $i => $pipe) {
            $firstPipe = new $pipe($pipes[$i+1] ?? null);
        }

        $this->pipe = $firstPipe;

        return $this;
    }

    /**
     * @return LinkedList|null
     */
    public function run()
    {
        return $this->pipe ? $this->pipe->handle($this->data) : null;
    }

}
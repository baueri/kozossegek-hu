<?php


namespace Framework\Support;


class LinkedList
{
    /**
     * @var LinkedList|null
     */
    protected $next;

    /**
     * @param LinkedList|null $next
     */
    public function __construct(?LinkedList $next)
    {
        $this->setNext($next);
    }

    /**
     * @param $next
     * @return LinkedList
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * @return LinkedList|null
     */
    public function getNext()
    {
        return $this->next;
    }
}
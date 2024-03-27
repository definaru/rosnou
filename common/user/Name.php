<?php

namespace common\user;

class Name
{
    /**
     * @var
     */
    private $first;

    /**
     * @var
     */
    private $last;

    /**
     * @var
     */
    private $middle;

    public function __construct($first, $last, $middle)
    {
        $this->first = $first;
        $this->last = $last;
        $this->middle = $middle;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->first;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return $this->last;
    }

    /**
     * @return mixed
     */
    public function middle()
    {
        return $this->middle;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s %s %s', $this->last(), $this->first(), $this->middle());
    }
}
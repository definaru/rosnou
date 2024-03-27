<?php

namespace domain\valueObject;

class Url extends AbstractValueObject
{
    /**
     * @var
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->url;
    }
}
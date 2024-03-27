<?php

namespace domain\valueObject;

abstract class AbstractValueObject
{
    /**
     * @return mixed
     */
    abstract public function getValue();
}
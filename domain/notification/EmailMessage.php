<?php

namespace domain\notification;

interface EmailMessage
{
    /**
     * @return mixed
     */
    public function view();

    /**
     * @return array
     */
    public function data() : array;

    /**
     * @return mixed
     */
    public function from();

    /**
     * @return mixed
     */
    public function to();

    /**
     * @return string
     */
    public function subject() : string;
}
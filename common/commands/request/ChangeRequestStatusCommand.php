<?php

namespace common\commands\request;

use common\models\RateRequest;

class ChangeRequestStatusCommand
{
    /**
     * @var RateRequest
     */
    private $rateRequest;

    /**
     * @var int
     */
    private $newStatus;

    public function __construct(RateRequest $rateRequest, int $newStatus)
    {
        $this->rateRequest = $rateRequest;
        $this->newStatus = $newStatus;
    }

    /**
     * @return RateRequest
     */
    public function rateRequest(): RateRequest
    {
        return $this->rateRequest;
    }

    /**
     * @return int
     */
    public function newStatus(): int
    {
        return $this->newStatus;
    }
}
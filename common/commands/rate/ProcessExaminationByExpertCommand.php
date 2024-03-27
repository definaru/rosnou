<?php

namespace common\commands\rate;

use common\models\RateRequest;

class ProcessExaminationByExpertCommand
{
    /**
     * @var RateRequest
     */
    private $rateRequest;

    /**
     * @var array
     */
    private $results;

    /**
     * @var bool
     */
    private $isDraft;

    public function __construct(RateRequest $rateRequest, array $results, bool $isDraft)
    {
        $this->rateRequest = $rateRequest;
        $this->results = $results;
        $this->isDraft = $isDraft;
    }

    /**
     * @return RateRequest
     */
    public function rateRequest() : RateRequest
    {
        return $this->rateRequest;
    }

    /**
     * @return array
     */
    public function results() : array
    {
        return $this->results;
    }

    /**
     * @return bool
     */
    public function isDraft() : bool
    {
        return $this->isDraft;
    }
}
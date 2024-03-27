<?php

namespace common\commands\handlers\request;

use common\commands\request\ChangeRequestStatusCommand;
use domain\rate\services\RateRequestService;

class ChangeRequestStatusHandler
{
    /**
     * @var RateRequestService
     */
    private $rateRequestService;

    public function __construct(RateRequestService $rateRequestService)
    {
        $this->rateRequestService = $rateRequestService;
    }

    public function handle(ChangeRequestStatusCommand $command)
    {
        $this->rateRequestService->changeStatus($command->rateRequest(), $command->newStatus());

        return $this;
    }
}
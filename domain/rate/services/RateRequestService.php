<?php

namespace domain\rate\services;

use domain\Exception;
use common\models\Site;
use common\models\RatePeriod;
use common\models\RateRequest;
use domain\ModelSaveException;
use domain\request\Status;

class RateRequestService
{
    /**
     * @var Status
     */
    private $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    /**
     * @param Site $site
     * @return RateRequest
     * @throws Exception
     * @throws ModelSaveException
     */
    public function send(Site $site)
    {
        $currentPeriod = RatePeriod::find()->active()->one();

        if(!$currentPeriod) {
            throw new Exception("No active period");
        }

        $rateRequest = new RateRequest();
        $rateRequest->site_id = $site->id;
        $rateRequest->period_id = $currentPeriod->id;
        $rateRequest->status_index = RateRequest::STATUS_ON_MODERATION;
        $rateRequest->created_datetime = date('Y-m-d H:i:s');

        if(!$rateRequest->save()) {
            throw new ModelSaveException($rateRequest);
        }

        return $rateRequest;
    }

    /**
     * @param RateRequest $request
     * @param int $status
     * @return $this
     */
    public function changeStatus(RateRequest $rateRequest, int $status)
    {
        if(!$this->status->validateStatusChange($rateRequest->status_index, $status)) {
            throw new Exception("Invalid status change");
        }

        $rateRequest->setStatus($status);

        if(!$rateRequest->save()) {
            throw new ModelSaveException($rateRequest);
        }

        return $this;
    }
}
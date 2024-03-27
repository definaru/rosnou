<?php

namespace domain\request;

use domain\Exception;
use common\models\RateRequest;

class Status
{
    /**
     * @param int $from
     * @param int $to
     * @return bool
     * @throws Exception
     */
    public function validateStatusChange(int $from, int $to)
    {
        $flow = $this->flow();

        if(!RateRequest::validateStatus($from)) {
            throw new Exception("Invalid initial status");
        }

        if(!RateRequest::validateStatus($to)) {
            throw new Exception("Invalid status to change");
        }

        return in_array($to, $flow[$from]);
    }

    /**
     * @return array
     */
    public function flow()
    {
        return [
            RateRequest::STATUS_APPROVED => [
            ],
            RateRequest::STATUS_ON_MODERATION => [
                RateRequest::STATUS_APPROVED,
                RateRequest::STATUS_DENIED,
                RateRequest::STATUS_ON_MODERATION,
            ],
            RateRequest::STATUS_DENIED => [
                RateRequest::STATUS_ON_MODERATION,
            ],
        ];
    }
}
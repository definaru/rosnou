<?php

namespace frontend\rating\widgets\RateRequest;

use common\models\RateRequest;
use yii\base\Widget;

class Status extends Widget
{
    /**
     * @var RateRequest
     */
    public $request;

    public function run()
    {
        return $this->render('status', ['name' => $this->statusName($this->request->status_index)]);
    }

    /**
     * @param $status
     * @return mixed
     */
    private function statusName($status)
    {
        return RateRequest::statusOptions()[$status];
    }
}
<?php

namespace common\models;

use common\components\ActiveQuery;

class RatePeriodQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->where('active_flag = 1');
    }
}
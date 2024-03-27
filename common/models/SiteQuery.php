<?php

namespace common\models;

use common\components\ActiveQuery;

class SiteQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function approved()
    {
        return $this;
    }

    /**
     * @param int $userId
     * @return $this
     */
    public function byUserId(int $userId)
    {
        return $this->where('user_id = :user_id', ['user_id' => $userId]);
    }
}
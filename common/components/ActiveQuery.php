<?php

namespace common\components;

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @param string $field
     * @param $value
     * @return $this
     */
    public function byField(string $field, $value)
    {
        return $this->andWhere("$field = :$field", [$field => $value]);
    }
}
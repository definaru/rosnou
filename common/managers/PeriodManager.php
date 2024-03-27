<?php

namespace common\managers;

use common\models\RatePeriod;
use common\models\RateCriteriaType;
use common\models\RateCriteria;
use domain\ModelSaveException;

class PeriodManager
{
    /**
     * Устанавливает флаг активности у периода
     * @param type $model
     * @return type
     */
    public function setActiveFlag(RatePeriod $model)
    {
        if ($model->active_flag) {
            // UPDATE active_flag
            $connection = \Yii::$app->db;
            $connection->createCommand()->update(\common\models\RatePeriod::tableName(), ['active_flag' => 0], ['<>', 'id', $model->id])->execute();
        }
    }
    /**
     * Создание копии периода
     * @param RatePeriod $model
     * @return type
     */
    public function copyPeriod(RatePeriod $model)
    {
        $Period = new RatePeriod;
        $Period->attributes = $model->attributes;
        $Period->title .= ' копия';
        $Period->slug .= '-copy';
        $Period->active_flag = 0;
        unset($Period->created_datetime); 

        if (!$Period->save()) {
            throw new ModelSaveException($Period);
        }

        $CriteriaTypes = RateCriteriaType::find()->where(['period_id' => $model->id])->all();

        foreach ($CriteriaTypes as $item) {
            $CriteriaType = new RateCriteriaType;
	        $CriteriaType->attributes = $item->attributes;
	        $CriteriaType->period_id = $Period->id;

	        if (!$CriteriaType->save()) {
	            throw new ModelSaveException($CriteriaType);
	        } 

	        $Criterias = RateCriteria::find()->where(['type_id' => $item->id])->all();	

	        foreach ($Criterias as $itemCriteria) {
	            $Criteria = new RateCriteria;
		        $Criteria->attributes = $itemCriteria->attributes;
		        $Criteria->type_id = $CriteriaType->id;
		        unset($Criteria->created_datetime);
		        
		        if (!$Criteria->save()) {
		            throw new ModelSaveException($Criteria);
		        } 
		    }

        } 
    }
}

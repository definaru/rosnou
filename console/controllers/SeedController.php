<?php

namespace console\controllers;

use common\models\RateCriteria;
use common\models\RateCriteriaResult;
use common\models\RateCriteriaResultComment;
use common\models\RateCriteriaType;
use common\models\RatePeriod;
use domain\ModelSaveException;
use yii\console\Controller;

class SeedController extends Controller
{
    public function actionCriterias()
    {
        $activePeriod = RatePeriod::find()->active()->one();

        if(!$activePeriod) {
            $this->stdout("No active period");

            return;
        }

        RateCriteriaResultComment::deleteAll();
        RateCriteriaResult::deleteAll();
        RateCriteria::deleteAll();
        RateCriteriaType::deleteAll();

        foreach(glob(\Yii::$app->basePath . '/data/criterias/*') as $file) {
            $siteTypeId = pathinfo($file, PATHINFO_FILENAME);

            $data = require $file;

            foreach($data as $item) {
                $criteriaType = new RateCriteriaType();
                $criteriaType->period_id = $activePeriod->id;
                $criteriaType->site_type_id = $siteTypeId;
                $criteriaType->title = $item['type'];
                $criteriaType->save();

                foreach($item['criterias'] as $id => $criteriaData) {
                    $criteria = new RateCriteria();
                    $criteria->type_id = $criteriaType->id;
                    $criteria->title = $criteriaData[0];
                    $criteria->score = $criteriaData[1];

                    if(!$criteria->save()) {
                        throw new ModelSaveException($criteria);
                    }
                }
            }
        }
    }
}
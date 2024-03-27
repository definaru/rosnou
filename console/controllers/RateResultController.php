<?php

namespace console\controllers;

use common\models\RateCriteriaResult;
use common\models\RateCriteriaResultComment;
use yii\console\Controller;

class RateResultController extends Controller
{
    public function actionRecalculateCommentsCount()
    {
        foreach(RateCriteriaResult::find()->all() as $rateCriteriaResult) {
            $rateCriteriaResult->comment_count = RateCriteriaResultComment::find()
                ->where('result_id = ' . $rateCriteriaResult->id)
                ->count();

            $rateCriteriaResult->save();
        }
    }
}
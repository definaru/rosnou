<?php

namespace frontend\rating\managers;

use \common\models\Site;
use \common\models\RatePeriod;
use \common\models\RateRequest;
use \common\models\RateCriteriaResult;
use \common\models\RateCriteriaTypeResult;
use yii\web\NotFoundHttpException;

class ResultManager
{

    private function calcByRequest(RateRequest $Request) {

        $QueryResult = RateCriteriaResult::find();
        $QueryResult->where(['request_id' => $Request->id]);

        $Results = $QueryResult->all();
        $CriteriaTypes = [];

        $ScoreAll = 0;

        foreach ($Results as $Result) {

            $score = $Result->status_index == RateCriteriaResult::STATUS_YES ? $Result->criteria->score : 0;

            if (!isset($CriteriaTypes[$Result->criteria->type->id])) {
                $CriteriaTypes[$Result->criteria->type->id] = 0;
            } 
            
            $CriteriaTypes[$Result->criteria->type->id] += $score;
            $ScoreAll += $score;
        }

        foreach ($CriteriaTypes as $TypeId => $TypeValue) {

            $RateCriteriaTypeResult = RateCriteriaTypeResult::findOne([
                'site_id' => $Request->site_id, 
                'criteria_type_id' => $TypeId, 
            ]);

            if (!$RateCriteriaTypeResult) {
                $RateCriteriaTypeResult = new RateCriteriaTypeResult();

                $RateCriteriaTypeResult->site_id =  $Site->id;
                $RateCriteriaTypeResult->criteria_type_id = $TypeId;
            } 

            $RateCriteriaTypeResult->score = $TypeValue; 

            if (!$RateCriteriaTypeResult->save()) {
                print_r($RateCriteriaTypeResult->errors);
                die;
            } 
        }

        $Request->score = $ScoreAll;
        $Request->save();
    }
    /*
     * Считаем кол-во баллов для самообследования для определенного периода
     */
    public function calcSites(RatePeriod $Period){
        if ($Period->freeze_result_flag) {
             throw new Exception("Пересчет данных периода '{$RatePeriod->title}' запрещен");
        }

        $QueryResult = RateCriteriaResult::find();
        $QueryResult->select('request_id');
        $QueryResult->groupBy(['request_id']);
        $Results = $QueryResult->all();

        foreach ($Results as $Result) {            
            $this->calcByRequest($Result->request);
        }
    }
    /*
     * Считаем кол-во баллов для самообследования для определенного сайта и периода
     */
    public function calcSite(Site $Site, RatePeriod $Period) {

        if ($Period->freeze_result_flag) {
             throw new Exception("Пересчет данных периода '{$RatePeriod->title}' запрещен");
        }

        $Request = RateRequest::findOne(['site_id' => $Site->id, 'period_id' => $Period->id]);

        if(!$Request) {
            throw new NotFoundHttpException();
        }

        $this->calcByRequest($Request);
    }
}

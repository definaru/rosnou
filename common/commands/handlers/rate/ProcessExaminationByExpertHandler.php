<?php

namespace common\commands\handlers\rate;

use common\components\EmailNotification;
use common\emails\SiteExaminationFinishedMessage;
use common\models\RateCriteria;
use common\models\RateCriteriaResult;
use common\models\RateCriteriaType;
use common\models\RateRequest;
use domain\ModelSaveException;
use yii\helpers\ArrayHelper;
use common\commands\rate\ProcessExaminationByExpertCommand;

class ProcessExaminationByExpertHandler
{
    /**
     * @var EmailNotification
     */
    private $emailNotification;

    public function __construct(EmailNotification $emailNotification)
    {
        $this->emailNotification = $emailNotification;
    }

    /**
     * @param ProcessExaminationByExpertCommand $command
     * @return $this
     * @throws ModelSaveException
     */
    public function handle(ProcessExaminationByExpertCommand $command)
    {
        // очищаем списковые поля данного запроса
        $listResults = RateCriteriaResult::find()
            ->where([
                'request_id' => $command->rateRequest()->id,
                'criteria_id' => RateCriteria::find()->where(['field_type' => RateCriteria::CRITERIA_TYPE_LIST])->select('id')
            ])
            ->all();

        foreach ($listResults as $CriteriaResult) {
            $CriteriaResult->status_index = 0;
            $CriteriaResult->update(false);
        }
        //
        //

        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id AND site_type_id = :site_type_id', [
                'period_id' => $command->rateRequest()->period->id,
                'site_type_id' => $command->rateRequest()->site->type_id,
            ])
            ->all();

        $criteriasItems = $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', ArrayHelper::map($criteriaTypes, 'id', 'id')])
            ->all();

        $criteriasItems = ArrayHelper::index($criteriasItems, 'id');

        $expertResults = $command->results();
        $validCriteriasIds = [];

        foreach($expertResults as $idx => $jsonResult) {
            $dataResult = json_decode($jsonResult, true);
            $validCriteriasIds[] = (int) $dataResult['id'];
        }

        $totalScore = 0;

        foreach($expertResults as $idx => $jsonResult) {

            $dataResult = json_decode($jsonResult, true);

            $criteriaId = $dataResult['id'];
            $expertResult = $dataResult['value'];

            if(in_array($criteriaId, $validCriteriasIds)) {

                $criteriaResult = RateCriteriaResult::find()
                    ->where('criteria_id = :criteria_id AND request_id = :request_id', [
                        'criteria_id' => $criteriaId,
                        'request_id' => $command->rateRequest()->id
                    ])->one();

                if( !$criteriaResult ){
                    $criteriaResult = new RateCriteriaResult();
                    $criteriaResult->criteria_id = $criteriaId;
                    $criteriaResult->request_id = $command->rateRequest()->id;
                }

                $criteriaResult->status_index = $expertResult > 0 ? RateCriteriaResult::STATUS_YES : RateCriteriaResult::STATUS_NO;

                $totalScore += $criteriaResult->isYes() ? $criteriasItems[$criteriaId]->score : 0;

                if(!$criteriaResult->save()) {

                    //print_r($jsonResult);
                    //print_r($dataResult);
                    //var_dump($criteriaId);
                    //var_dump($criteriaResult->attributes);die;

                    throw new ModelSaveException($criteriaResult);
                }

                //echo "update criteria\n";
            }
        }

        //echo 'stop';
        //die;

        $rateRequest = $command->rateRequest();

        $sendNotification = false;

        if($command->isDraft()) {
            $rateRequest->setStatus(RateRequest::STATUS_EXPERT_DRAFT);
        } else {
            $rateRequest->score = $totalScore;
            $rateRequest->setStatus(RateRequest::STATUS_CHECKED);
            $sendNotification = true;
        }

        if(!$rateRequest->save()) {
            throw new ModelSaveException($rateRequest);
        } 
        
        if($sendNotification) {
            $this->emailNotification->send(new SiteExaminationFinishedMessage($rateRequest->site->user, $rateRequest->site));
        }

        return $this;
    }
}
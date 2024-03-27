<?php

namespace common\commands\handlers\rate;

use common\commands\rate\SaveSelfExamination as SaveSelfExaminationCommand;
use common\models\RateCriteria;
use common\models\RateCriteriaResult;
use common\models\RateCriteriaType;
use common\models\RateRequest;
use domain\ModelSaveException;
use yii\helpers\ArrayHelper;

class SaveSelfExamination
{
    /**
     * @param SaveSelfExaminationCommand $command
     * @return $this
     * @throws ModelSaveException
     */
    public function handle(SaveSelfExaminationCommand $command)
    {
        $validCriteriasIds = $this->validCriteriasIds($command);

        $this->processResults($command, $validCriteriasIds);

        $this->createEmptyResults($validCriteriasIds, $command->results(), $command);

        $this->updateRateRequest($command);

        return $this;
    }

    /**
     * @param SaveSelfExaminationCommand $command
     * @return $this
     * @throws ModelSaveException
     */
    private function updateRateRequest(SaveSelfExaminationCommand $command)
    {
        $rateRequest = $command->rateRequest();

        if($command->isDraft()) {
            $rateRequest->setStatus(RateRequest::STATUS_USER_DRAFT);
        } else {
            $rateRequest->setStatus(RateRequest::STATUS_FINISHED);

            $query = RateRequest::find()
                ->where('period_id = :period_id', [
                    'period_id' => $command->rateRequest()->period->id,
                ]);
                        
            if ($command->rateRequest()->expert_id) {
                $query->andWhere(['OR', ['status_index' => RateRequest::STATUS_FINISHED], ['status_index' => RateRequest::STATUS_EXPERT_DRAFT]]);
                $query->andWhere(['expert_id' => $command->rateRequest()->expert_id]);
            } else {
                $query->andWhere(['IS', 'expert_id', null]);
                $query->andWhere(['status_index' => RateRequest::STATUS_FINISHED]);   
            }
            $queueIndex = $query->max('queue_index');

            $rateRequest->queue_index = $queueIndex + 1;
        }

        if(!$rateRequest->save()) {
            throw new ModelSaveException($rateRequest);
        }

        return $this;
    }

    private function processResults(SaveSelfExaminationCommand $command, array $validCriteriasIds)
    {
        foreach($command->results() as $criteriaId => $url) {
            if(in_array($criteriaId, $validCriteriasIds)) {
                $criteriaResult = RateCriteriaResult::find()
                    ->where('criteria_id = :criteria_id AND request_id = :request_id', [
                        'criteria_id' => $criteriaId,
                        'request_id' => $command->rateRequest()->id
                    ])->one();

                if(!$criteriaResult) {
                    $criteriaResult = new RateCriteriaResult();
                    $criteriaResult->criteria_id = $criteriaId;
                    $criteriaResult->request_id = $command->rateRequest()->id;
                }

                $criteriaResult->url = $url;

                if(!$criteriaResult->save()) {
                    throw new ModelSaveException($criteriaResult);
                }
            }
        }
    }

    /**
     * @param SaveSelfExaminationCommand $command
     * @return array
     */
    private function validCriteriasIds(SaveSelfExaminationCommand $command)
    {
        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id AND site_type_id = :site_type_id', [
                'period_id' => $command->rateRequest()->period->id,
                'site_type_id' => $command->rateRequest()->site->type_id,
            ])
            ->all();

        $criteriasItems = $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', ArrayHelper::map($criteriaTypes, 'id', 'id')])
            ->all();

        return array_keys(ArrayHelper::index($criteriasItems, 'id'));
    }

    /**
     * @param array $validCriteriasIds
     * @param array $criteriaResults
     * @param SaveSelfExaminationCommand $command
     * @return $this
     * @throws ModelSaveException
     */
    private function createEmptyResults(array $validCriteriasIds, array $criteriaResults, SaveSelfExaminationCommand $command)
    {
        $emptyCriterias = array_diff($validCriteriasIds, array_keys($criteriaResults));

        foreach($emptyCriterias as $criteriaId) {

            $criteriaResult = RateCriteriaResult::find()
                ->where('criteria_id = :criteria_id AND request_id = :request_id', [
                    'criteria_id' => $criteriaId,
                    'request_id' => $command->rateRequest()->id
                ])->one();
            if (!$criteriaResult) {        
                $criteriaResult = new RateCriteriaResult();
                $criteriaResult->criteria_id = $criteriaId;
                $criteriaResult->request_id = $command->rateRequest()->id;

                if(!$criteriaResult->save()) {
                    throw new ModelSaveException($criteriaResult);
                }
            }
        }

        return $this;
    }
}
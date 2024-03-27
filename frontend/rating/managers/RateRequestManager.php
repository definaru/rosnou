<?php
namespace frontend\rating\managers;

use Yii;
use common\models\User;
use common\models\Site;
use common\models\RateRequest;
use common\models\RateCriteria;
use common\models\RateCriteriaResult;
use common\models\RateCriteriaType;

use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\db\Query;

class RateRequestManager {

    private $PeriodManager = null;
    private $User = null;
    private $db = null;
    private $VkontakteManager = null;

    public function __construct(PeriodManager $PeriodManager, User $User, $db, $VkontakteManager) {
        $this->PeriodManager = $PeriodManager;
        $this->User = $User;
        $this->db = $db;
        $this->VkontakteManager = $VkontakteManager;
    }
    /*
    * Обновление очереди экспертизы сайтов не привязанных к экспертам
    */
    public function updateQueue() {

        $query = RateRequest::find();
        $query->andWhere(['status_index' => RateRequest::STATUS_FINISHED]);
        $query->andWhere(['IS', 'expert_id', null]);
        $query->andWhere(['period_id' => $this->PeriodManager->getActivePeriod()->id]);
        $query->orderBy('queue_index');

        $RateRequests = $query->all();

        $index = 1;

        foreach ($RateRequests as $RateRequest) {

            $this->db->createCommand()
            ->update(RateRequest::tableName(), ['queue_index' => $index], ['id' => $RateRequest->id])
            ->execute();

            $index++;
        }
    }

        /*
    * Обновление очереди экспертизы сайтов для эксперта
    */
    public function updateExpertQueue() {

        $query = RateRequest::find();
        $query->andWhere(['OR', ['status_index' => RateRequest::STATUS_FINISHED], ['status_index' => RateRequest::STATUS_EXPERT_DRAFT]]);
        $query->andWhere(['expert_id' => $this->User->id]);
        $query->andWhere(['period_id' => $this->PeriodManager->getActivePeriod()->id]);
        $query->orderBy('queue_index');

        $RateRequests = $query->all();

        $index = 1;

        foreach ($RateRequests as $RateRequest) {

           $this->db->createCommand()
            ->update(RateRequest::tableName(), ['queue_index' => $index], ['id' => $RateRequest->id])
            ->execute();
            
            $index++;

        }

    }

    public function getMaxQueueExpert($expert_id) {

            $query = RateRequest::find()
                ->where('period_id = :period_id', [
                    'period_id' => $this->PeriodManager->getActivePeriod()->id,
                ]);
                        
            $query->andWhere(['OR', ['status_index' => RateRequest::STATUS_FINISHED], ['status_index' => RateRequest::STATUS_EXPERT_DRAFT]]);
            $query->andWhere(['expert_id' => $expert_id]);

            $queueIndex = $query->max('queue_index');

            return $queueIndex;
    }

    /**
     * На основе запроса формируем массив
     * из списка критериев с результатами
     * сгруппированных по типам
     */
    public function loadRateResultList(RateRequest $RateRequest){

        // Criteria types
        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id AND site_type_id = :site_type_id', [
                'period_id' => $RateRequest->period->id,
                'site_type_id' => $RateRequest->site->type_id,
            ])
            ->orderBy('id ASC')
            ->asArray()
            ->all();

        $types = ArrayHelper::map($criteriaTypes, 'id', 'id');

        // Criteria items
        $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', $types])
            ->orderBy('title ASC')
            ->asArray()
            ->all();

        $criterias = ArrayHelper::index($criteriasItems, null, 'type_id');;

        // Criteria results
        $criteriaResults = RateCriteriaResult::find()
            ->where(['request_id' => $RateRequest->id])
            ->asArray()
            ->all();

        $criteriaResults = ArrayHelper::index($criteriaResults, 'criteria_id');

        // Формируем массив
        $results = [];

        $resultInit = [
            'id' => null,
            'checkedYes' => null,
            'checkedNo' => null,
            'comment_count' => 0,
            'url' => null,
            'status_index' => 0,
        ];

        foreach($criteriaTypes as $type){

            $typeId = $type['id'];
            $results[$typeId] = $type;
            $results[$typeId]['criteriaList'] = $criterias[$typeId] ?? [];

            foreach( $results[$typeId]['criteriaList'] as $criteriaIdx => $criteria){
                
                $criteriaId = $criteria['id'];

                $result = $criteriaResults[$criteriaId] ?? $resultInit;

                // Отмечаем выделенные пункты
                if( $result ){

                    $result['checkedYes'] = $result['status_index'] == RateCriteriaResult::STATUS_YES;
                    $result['checkedNo'] = ($result['status_index'] == RateCriteriaResult::STATUS_NO || (!$type['hidden_flag'] && !$result['url']) );

                    /**
                     * 
                     * Непонятный функционал!!!!
                     *
                    if ($RateRequest->status_index == RateRequest::STATUS_FINISHED && $type['hidden_flag']) {
                        $result['checkedYes'] = false;
                        $result['checkedNo'] = false;
                    }
                    **/
                
                    // Извлекаем данные в соответствии с функцией, установленной критерию 
                    $result['function_data'] = [];

                    if( $criteria['function'] == 'vk_group_link' ){
                        $result['function_data'] = $this->socialVkData($result['url']);
                    }

                }

                $results[$typeId]['criteriaList'][$criteriaIdx]['result'] = $result;
            }
        }

        //print_r($results);die;

        return $results;
    }


    private function socialVkData($url){

        $parts = explode('/', $url);
        $groupName = end($parts);
        
        // Удалаляем ключ public для получения id группы
        if( strpos($groupName, 'public') === 0 ){
            $groupName = substr($groupName, 6);
        }

        $result = [];

        $groupData = $this->VkontakteManager->getGroupData($groupName);

        //var_dump($groupData);die;

        $groupId = $groupData['id'] ?? null;

        if( $groupId ){

            $groupId = $groupData['id']; 

            $wall_stat = $this->VkontakteManager->getWallStat($groupId);

            //print_r($wall_stat);die;

            $result[] = [
                'title' => 'Название группы',
                'value' => $groupData['name']
            ];

            $result[] = [
                'title' => 'Кол-во участников',
                'value' => $this->VkontakteManager->getGroupMembersCount($groupId)
            ];

            $result[] = [
                'title' => 'Минимум реакций (комент+лайк+репост) за последние 100 постов',
                'value' => $wall_stat['action_min_count'],
            ];

            $result[] = [
                'title' => 'Среднее колличество публикаций в неделю на основе 100 последних постов',
                'value' => $wall_stat['post_weekavg_count'],
            ];

            return $result;

        }

        /**
         *
        
        https://api.vk.com/method/groups.getMembers?group_id=tproger&v=5.73&count=0&access_token=e993b281e993b281e993b2817be9f1bdf6ee993e993b281b32982f56b3f306db2423866
        
        https://api.vk.com/method/groups.getById?v=5.73&group_ids=tproger&access_token=e993b281e993b281e993b2817be9f1bdf6ee993e993b281b32982f56b3f306db2423866
        
        https://api.vk.com/method/wall.get?v=5.73&count=100&owner_id=6426487&access_token=e993b281e993b281e993b2817be9f1bdf6ee993e993b281b32982f56b3f306db2423866

         e993b281e993b281e993b2817be9f1bdf6ee993e993b281b32982f56b3f306db2423866
         */




    }

}

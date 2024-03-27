<?php
namespace frontend\rating\managers;

use Yii;
use common\models\User;
use common\models\Site;
use yii\db\ActiveQuery;
use yii\db\Query;

class SiteRequestManager {

    private $PeriodManager = null;
    private $User = null;
    private $db = null;

    public function __construct(PeriodManager $PeriodManager, User $User, $db) {
        $this->PeriodManager = $PeriodManager;
        $this->User = $User;
        $this->db = $db;
    }
    /*
    * Обновление очереди модерации сайтов
    */
    public function updateQueue() {
        $query = Site::find();
        $query->andWhere(['status_index' => Site::STATUS_ON_MODERATION]);
        $query->orderBy('queue_index');

        $Sites = $query->all();

        $index = 1;
        foreach ($Sites as $Site) {

            $this->db->createCommand()
            ->update(Site::tableName(), ['queue_index' => $index], ['id' => $Site->id])
            ->execute();

            $index++;
        }

    }

    /*
    * Список сайтов пользователя
    */
    public function loadUserSiteList() {
        
        $list = [];

        $activePeriod = $this->PeriodManager->getActivePeriod();

        $Sites = Site::find()->orderBy('id DESC')
        ->with(['activeRequest' => function(ActiveQuery $query) use($activePeriod) {
            return $query->andWhere('period_id = ' . ($activePeriod ? $activePeriod->id : 0));
        }])
        ->byUserId($this->User->id)
        ->all();
        foreach ($Sites as $Site) {

            $item = [];
            $item['id'] = $Site->id;
            $item['title'] = $Site->title;
            $item['domain'] = $Site->domain;
            $item['site_status'] = $Site->getStatusIndexList($Site->status_index);
            
            $item['site_status_approved'] = $Site->isStatus(\common\models\Site::STATUS_APPROVED);
            
            $item['request_not_finished'] = !$Site->activeRequest || $Site->activeRequest->isStatus(\common\models\RateRequest::STATUS_NOT_FINISHED);
            
            $item['request_finished'] = $Site->activeRequest ? $Site->activeRequest->isStatus(\common\models\RateRequest::STATUS_FINISHED) : 0;
            $item['request_expert_draft'] = $Site->activeRequest ? $Site->activeRequest->isStatus(\common\models\RateRequest::STATUS_EXPERT_DRAFT) : 0;
            $item['request_checked'] = $Site->activeRequest ? $Site->activeRequest->isStatus(\common\models\RateRequest::STATUS_CHECKED) : 0;
            $item['site_status_on_moderation'] = $Site->isStatus(\common\models\Site::STATUS_ON_MODERATION) && $Site->queue_index;
            
            $item['queue_index'] = $Site->queue_index;
            
            $item['request_user_draft'] = $Site->activeRequest ? $Site->activeRequest->isStatus(\common\models\RateRequest::STATUS_USER_DRAFT) : 0;
            $item['request_queue_index'] = $Site->activeRequest ? $Site->activeRequest->queue_index : 0;
            $item['request_score'] = $Site->activeRequest ? $Site->activeRequest->score : 0;
            $item['type_id'] = $Site->type_id;

            $list[] = $item;
        }

        return $list;
    }
}

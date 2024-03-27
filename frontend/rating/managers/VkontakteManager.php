<?php
namespace frontend\rating\managers;

use Yii;

use common\models\User;
use common\models\Site;
use common\models\RateRequest;
use common\models\RatePeriod;
use common\models\RateCriteriaType;
use common\models\RateCriteria;

use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\db\Query;

class VkontakteManager {

    private $User = null;
    private $Curl = null;

    /**
     * Сервисный ключ
     * не имеет срока действия и доступен в настройках приложения
     */
    private $serviceToken = 'e993b281e993b281e993b2817be9f1bdf6ee993e993b281b32982f56b3f306db2423866';

    public function __construct(User $User, $Curl) {
        $this->User = $User;
        $this->Curl = $Curl;
    }

    private function request($method, $queryParams){

        $queryParams['access_token'] = $this->serviceToken;

        $query = http_build_query($queryParams);

        //print_r($query);die;

        $response = $this->Curl->download("https://api.vk.com/method/{$method}?{$query}");

        //print_r($response);die;

        $json = json_decode($response, true);

        return [
            'string' => $response,
            'json' => $json,
        ];

    }


    /**
     * Возвращает информацию о группе
     */
    public function getGroupData($groupName){

        $response = $this->request('groups.getById', [
            'group_id' => $groupName, 
            'v' => '5.60',
            'count' => 0]);

        $json = $response['json'];

        return $json['response'][0] ?? false;


    }

    /**
     * Колличество пользователей группы
     */
    public function getGroupMembersCount(int $groupId){

        $response = $this->request('groups.getMembers', [
            'group_id' => $groupId, 
            'v' => '5.73',
            'count' => 0]);

        $json = $response['json'];

        return $json['response']['count'] ?? 0;
    }

    /**
     *
     */
    private function addItems(&$items, int $groupId, $offset){

        // page 1
        $response = $this->request('wall.get', [
            'owner_id' => '-' . $groupId, 
            'v' => '5.80',
            'count' => 100,
            'offset' => $offset,
        ]);

        $list = $response['json']['response']['items'] ?? [];
    
        foreach($list as $item){
            $items[] = $item;
        }
    }

    public function getWallStat(int $groupId){

        /**
         * идентификатор сообщества в параметре owner_id необходимо указывать со знаком "-" — например, owner_id=-1
         */
        $items = [];

        $this->addItems($items, $groupId, 0);
        //$this->addItems($items, $groupId, 100);
        //$this->addItems($items, $groupId, 200);
        
        //print_r($resp);die;

        $postCount = 0;
        $commentSumm = 0;

        // Минимльное колличество реакий на пост
        $stat['action_min_count'] = 0;

        if( isset($items[0]) ){
            $stat['action_min_count'] = $this->postActionSum($items[0]);
        }

        foreach($items as $item){
            $stat['action_min_count'] = min( $this->postActionSum($item), $stat['action_min_count']);
        }

        // Распределяем посты по неделям
        $time = time();
        $weeks = [];

        // Создаем индексы десяти прошедших полных недель
        //for($i = 1; $i <= 10; $i++){
        //    $weekIndex = date('YW', $time - $i * ( 24*60*60*7 ) );
        //    $weeks[$weekIndex] = ['posts_count' => 0]; 
        //}

        // Распределяем полученные посты по неделям
        $weekInit = ['post_count' => 0];

        foreach($items as $item){
            
            $weekIndex = date('YW', $item['date']);
            $weeks[$weekIndex] = $weeks[$weekIndex] ?? $weekInit;

            //if( isset($weeks[$weekIndex]) ){
            $weeks[$weekIndex]['post_count']++;
            //}
        }

        // Последнюю неделю выбрасываем, поскольку она может быть не полной
        $stat['post_weekavg_count'] = 0;

        if( sizeof($weeks) > 0 ){

            if( sizeof($weeks) > 1 ){
                $minWeekIndex = min( array_keys($weeks) );
                unset($weeks[$minWeekIndex]);
            }

            $post_sum = array_sum(ArrayHelper::getColumn($weeks, 'post_count'));
            $stat['post_weekavg_count'] = ceil($post_sum / sizeof($weeks));

        }

        // теперь вычисляем минимальное кол-во постов
        //$firstWeek = reset($weeks);
        //$stat['week_min_posts'] = $firstWeek['posts_count'];

        //foreach($weeks as $week){
        //    $stat['week_min_posts'] = min($stat['week_min_posts'], $week['posts_count']);
        //}

        //print_r($weeks);

        return $stat;
    }

    /**
     * Колл-во реакций на пост
     * лайки + репосты + комменты
     */
    private function postActionSum($item){
        
        $commentsCount = $item['comments']['count'] ?? 0;
        $likesCount = $item['likes']['count'] ?? 0;
        $repostsCount = $item['reposts']['count'] ?? 0;

        return $commentsCount + $likesCount + $repostsCount;
    }

}

<?php

namespace frontend\rating\managers;

use \common\models\RatePeriod;
use \common\models\RateRequest;
use \common\models\Site;
use \common\models\RateCriteriaType;
use \common\models\RateCriteriaResult;


class PeriodManager
{

    // получаем requests по завершившимся периодам
    public function loadFinishedRequests(Site $Site, $checkScore = false, $period_id = null) {

        $query = RateRequest::find()->alias('r');
        
        $query->where(['site_id' => $Site->id]);

        $query->innerJoinWith('period p');
        $query->andWhere(['p.finished_flag' => 1]);
        
        $query->orderBy('p.list_order desc');

        if ($checkScore) {
            $query->andWhere(['>', 'r.score','0']);
        }

        if ($period_id) {
            $query->andWhere(['p.id' => $period_id]);
        }

        $list = $query->all();

        return $list;
    }

    // получаем завершившиеся периоды
    public function loadFinishedPeriods() {

        $query = RatePeriod::find()->where(['finished_flag' => 1])->orderBy('list_order desc');
        $list = $query->all();

        return $list;
    }

    // получаем все периоды со всеми полями
    public function loadPeriodsList() {

        $query = RatePeriod::find();
        $query->select('
            id,title,extract(epoch from created_datetime)::int as created_timestamp,active_flag,
            request1_accept_flag,request2_accept_flag,register_accept_flag,finished_flag,freeze_result_flag,list_order

        ');
        $query->orderBy('list_order');


        $list = $query->asArray()->all();

        return $list;

    }
    // получаем все периоды (только title)
    public function loadPeriodsData() {

        $query = RatePeriod::find()->orderBy('list_order desc')->select('title')->indexBy('id');


        $list = $query->column();

        return $list;

    }

    // загрузка периода
    public function getPeriod($id) {

        $Period = RatePeriod::findOne($id);

        if(!$Period) {
            throw new NotFoundHttpException();
        }

        return $Period;
    }

    // загрузка периода по slug
    public function getPeriodBySlug($slug) {

        $Period = RatePeriod::findOne(['slug' => $slug]);

        if(!$Period) {
            throw new NotFoundHttpException();
        }

        return $Period;
    }

    // загрузка данных определенного периода для сайта
    public function loadPeriodData(RatePeriod $Period, Site $Site) {

        
        $query = RateCriteriaResult::find()->alias('r'); 
        
        $query->innerJoinWith('request req');
        $query->innerJoinWith('criteria c');
        $query->innerJoinWith('criteria.type t');
        
        $query->andWhere([
            'req.period_id' => $Period->id, 
            'req.site_id' => $Site->id
        ]);
        $query->andWhere(['req.status_index' => RateRequest::STATUS_CHECKED]);
        $query->select('t.id as type_id, t.title as type_title, c.id, c.title, r.status_index, c.score');

        $Criterias = $query->createCommand()->queryAll();

        $list = [];

        $type_id_current = null;
        $type_id_prev = null;

        $count = 0;
        $count_all = 0;
        
        foreach ($Criterias as $Criteria) {


            if ($Criteria['type_id'] != $type_id_current) {

                $type_id_prev = $type_id_current;
                $type_id_current = $Criteria['type_id'];
                $list[$Criteria['type_id']]['title'] = $Criteria['type_title'];
                $list[$Criteria['type_id']]['count'] = 0;

                if ($type_id_prev) {
                    $list[$type_id_prev]['count'] = $count;
                    $count_all += $count;
                    $count = 0; 
                }
            }

            // Критерий засчитан
            $result_status_ok = $Criteria['status_index'] == RateCriteriaResult::STATUS_YES ? true : false;

            if ( $result_status_ok ) {
                $count += $Criteria['score'];
            }    

            $list[$Criteria['type_id']]['list'][] = [
                'title' => $Criteria['title'], 
                'score' => $result_status_ok ? $Criteria['score'] : 0
            ];
        }

        if ($type_id_prev) {
            $list[$type_id_current]['count'] = $count;
            $count_all += $count;
            $count = 0; 
        }

        $count_all = $count_all;
        return [
            'list' => $list,
            'count_all' => $count_all,
        ];
    }
    // 
    // загрузка сайтов для периода и типа сайтов (которые есть в request)
    public function loadPeriodSites($periodID, $siteType) {

        $query = RateRequest::find()->alias('r');

        $query->joinWith('site s')->andWhere(['s.type_id' => $siteType]);;
        $query->joinWith('site.district d');
        $query->joinWith('site.subject sbj');
        $query->joinWith('period p')->andWhere(['p.id' => $periodID]);

        $query->select('s.id, r.score, s.title, s.location, s.domain, sbj.title as sbjTitle, d.title as distTitle, r.id as request_id, s.type_id as site_type_id');
        $query->andWhere(['r.status_index' => RateRequest::STATUS_CHECKED]);
        $query->andWhere(['s.status_index' => SITE::STATUS_APPROVED]);
        $query->orderBy('r.score desc');

        $list = $query->createCommand()->queryAll();

        return $list;

    }

    // загрузка сайтов для периода и типа сайтов (которые есть в request) по slug
    public function loadPeriodSitesSlug($periodSlug, $typeSlug) {

        $query = RateRequest::find()->alias('r');

        $query->joinWith('site s');
        $query->joinWith('site.type t')->andWhere(['t.slug' => $typeSlug]);
        $query->joinWith('site.district d');
        $query->joinWith('site.subject sbj');
        $query->joinWith('period p')->andWhere(['p.slug' => $periodSlug]);

        $query->select('s.id, r.score, s.title, s.location, s.domain, sbj.title as sbjTitle, d.title as distTitle, r.id as request_id, s.type_id as site_type_id');
        $query->andWhere(['r.status_index' => RateRequest::STATUS_CHECKED]);
        $query->andWhere(['s.status_index' => SITE::STATUS_APPROVED]);
        $query->orderBy('r.score desc, s.title');

        if( @$_GET['test1'] ){
	
		echo $query->createCommand()->rawSql;die;

	}

        $list = $query->createCommand()->queryAll();

        return $list;

    }

    // получаем активный период
    public function getActivePeriod() {

        $Period = RatePeriod::findOne(['active_flag' => 1]);

        return $Period;
    }
    
    // возможность регистрации
    public function acceptRegistration() {

        $activePeriod = $this->getActivePeriod();

        if ($activePeriod && $activePeriod->register_accept_flag == 1) {
            return true;
        }

        return false;
    }

    // возможность приема первичных заявок на самообследование
    public function AcceptRequest1(RatePeriod $activePeriod) {

        if ($activePeriod && $activePeriod->request1_accept_flag == 1) {
            return true;
        }

        return false;
    }

    // возможность приема повторных заявок на самообследование
    public function AcceptRequest2(RatePeriod $activePeriod) {

        if ($activePeriod && $activePeriod->request2_accept_flag == 1) {
            return true;
        }

        return false;
    }
}

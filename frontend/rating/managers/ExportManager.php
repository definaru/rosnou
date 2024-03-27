<?php

namespace frontend\rating\managers;

use common\models\Site;
use common\models\RateRequest;
use common\models\RateCriteriaResult;


class ExportManager
{
    private $ScoreRulesManager = null;

    public function __construct(ScoreRulesManager $ScoreRulesManager){
        $this->ScoreRulesManager = $ScoreRulesManager;
    }


    public function loadSitesDataJson($limit = 10, $offset = 0){

        $query = Site::find();
        $count = $query->count();

        $result = [];

        $result['count'] = $count;
        $result['limit'] = $limit;
        $result['offset'] = $offset;

        $query->limit($limit)->offset($offset)->select('id, domain')->orderBy('id');

        $sites = $query->asArray()->all();

        $result['sites'] = $sites;
        
        $json = json_encode($result, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $json;

    }

   	/**
     * Извлекаем данные рейтинга для домена по годам и формируем json
    */
    public function loadRatingDataJson(String $domain){

    	$result = [];

    	// http://rating-umi.edsites.ru/udata/users/results_by_years/(barilovkaschool.ucoz.ru%20)/.json

    	$QuerySite = Site::find()
            ->where(['ilike', 'domain', '//' . $domain])
            ->andWhere(['status_index' => 2]);

        $Site = $QuerySite->one();

        // нашли сайт
    	if ($Site) {

            // Получаем запросы на самообследование
    		$query = RateRequest::find()->alias('req');

    		$query->where([
                'req.site_id' => $Site->id, 
                'req.status_index' => [RateRequest::STATUS_FINISHED, RateRequest::STATUS_CHECKED],
            ]);
    		
            $query->innerJoinWith('period as p')->orderBy('p.created_datetime desc');
    		$query->innerJoinWith('criteriaResults');
    		$query->innerJoinWith('criteriaResults.criteria');
    		$query->innerJoinWith('criteriaResults.criteria.type');

            //echo $query->createCommand()->rawSql;die;

    		$Requests = $query->all();

    		if ( sizeof($Requests) > 0 ) {

                $result['status_id'] = $Site->status_index;
                $result['site_id'] = $Site->id;
                $result['host'] = $Site->domain;

                // статус последнего запроса
    			$result['self_examination']['timestamp'] = strtotime($Requests[0]->created_datetime);
                $result['self_examination']['period_id'] = $Requests[0]->period_id;
                $result['self_examination']['status_id'] = $Requests[0]->status_index;

                //$index = 0;
    			
                foreach ($Requests as $Request) {

    				$period = [];

                    $period_id = $Request->period->id;

    				//$period = date('Y', $selfexamData['unix-timestamp']);
                    $period['period_id'] = $period_id;
                    $period['active_flag'] = $Request->period->active_flag;
    				$period['name'] = $Request->period->title; //date('Y', strtotime($Request->period->created_datetime));
    				$period['current'] = $Request->score;
	    			$period['status'] = $Request->status_index;
                    $period['max'] = $this->ScoreRulesManager->getMaxScore($Request->period, $Site->type_id);
	    			$period['site_category'] = $Site->type->id;
	    			//$period['host'] = $domain;

                    // Данные самообследования за указанный период
                    $period['self_examination']['timestamp'] = strtotime($Request->created_datetime);
                    $period['self_examination']['status_id'] = $Request->status_index;

    				$result['periods'][$period_id] = $period;
			        //$query->select('t.id as type_id, t.title as type_title, c.id, c.title, r.status_index, c.score, c.sysname, c.title as criteria_title');
                    $group = [];

        			foreach ($Request->criteriaResults as $Criteria) {

                       // echo "== {$Criteria->criteria->type->title} ==";
                        $group[$Criteria->criteria->type->id]['title'] = $Criteria->criteria->type->title;
                        $group[$Criteria->criteria->type->id]['name'] = $Criteria->criteria->type->sysname . '_' . $Criteria->criteria->type->id;
                        $group[$Criteria->criteria->type->id]['group_id'] = $Criteria->criteria->type->id;

                        if (!isset($group[$Criteria->criteria->type->id]['value'])) {
                            $group[$Criteria->criteria->type->id]['value'] = 0;
                        }

                        $group[$Criteria->criteria->type->id]['value'] += $Criteria->status_index == 1 ? $Criteria->criteria->score : 0;

        			}
                    $result['periods'][$period_id]['groups'] = $group;
    			}

    		} else {
    			$result['error'] = "Requests not found. ({$domain})";	
    		}
    	} else {
    		$result['error'] = "Site not found. ({$domain})";
    	}
    	
        $json = json_encode($result, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    	return $json;
    }
}

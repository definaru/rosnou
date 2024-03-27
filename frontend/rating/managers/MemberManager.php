<?php

namespace frontend\rating\managers;

use frontend\rating\forms\MembersSearchForm;
use \common\models\Site;
use \common\models\SiteType;
use \common\models\SiteDistrict;
use \common\models\SiteSubject;
use \common\models\RatePeriod;
use yii\web\NotFoundHttpException;

class MemberManager
{
	public $orderBy = 's.title asc';

    public function search(MembersSearchForm $form) {


        $query = Site::find()->alias('s');
        $query->where([ 's.status_index' => Site::STATUS_APPROVED]);

        $query->innerJoinWith('type as t');
        $query->innerJoinWith('district as d');
        $query->innerJoinWith('subject as sbj');


        if ($form->orderBy) { 
            $this->orderBy = $form->orderBy;
        }

        if ($form->site_type_id) { 
            $query->andFilterWhere(['t.id' => $form->site_type_id]);
        }

        if ($form->district_id) { 
            $query->andFilterWhere(['d.id' => $form->district_id]);
        }

        if ($form->subject_id) { 
            $query->andFilterWhere(['sbj.id' => $form->subject_id]);
        }

    	$itemCount = $query->count();

        // Пагинация
        $pages = new \yii\data\Pagination([
            'totalCount' => $itemCount,
            //'defaultPageSize'=> 2,
            //'pageSize'=> 2 ,

        ]);

        // Список
        $query->offset($pages->offset)->limit($pages->limit);
        $query->orderBy($this->orderBy);

        $list = $query->all();

        return [
            'query' => $query,
            'list' => $list,
            'pages' => $pages,
            'count' => $itemCount,
        ];
    }

    // получаем участника рейтинко по id
    public function loadSiteData($id) {

        $query = Site::find()->where(['id' => $id,  'status_index' => Site::STATUS_APPROVED]);
        $query->with('district','subject');

        $Site = $query->one();

        if(!$Site) {
            throw new NotFoundHttpException();
        }

        return $Site;        
    }

}
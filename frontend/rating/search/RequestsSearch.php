<?php

namespace frontend\rating\search;

use common\models\RatePeriod;
use common\models\RateRequest;
use common\models\Site;
use common\models\SiteType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class RequestsSearch extends RateRequest
{
    public $siteType;

    public function rules()
    {
        return [
            [['siteType'], 'exist', 'targetClass' => SiteType::class, 'targetAttribute' => 'id', 'skipOnEmpty' => true],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, RatePeriod $period)
    {
        $query = RateRequest::find();

        $query->with('site');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,

            ],
            'sort' => ['attributes' => [
                'created_datetime',
            ]]
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if($this->siteType) {
            $sitesIds = ArrayHelper::map(
                Site::find()->where('type_id = :type_id', ['type_id' => $this->siteType])->all(),
                'id',
                'id'
            );

            $query->andWhere(['in', 'site_id', $sitesIds]);
        }

        $query->andFilterWhere([
            'period_id' => $period->id,
            //'id' => $this->id,
            //'type_id' => $this->type_id,
            //'status_index' => $this->status_index,
            //'user_id' => $this->user_id,
            //'created_timestamp' => $this->created_timestamp,
            //'district_id' => $this->district_id,
            //'subject_id' => $this->subject_id,
        ]);

        //$query->andFilterWhere(['like', 'domain', $this->domain])
        //    ->andFilterWhere(['like', 'title', $this->title])
        //    ->andFilterWhere(['like', 'org_title', $this->org_title])
        //    ->andFilterWhere(['like', 'location', $this->location])
        //    ->andFilterWhere(['like', 'headname', $this->headname]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'created_datetime' => 'Дата подачи',
        ];
    }
}
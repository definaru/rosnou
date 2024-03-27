<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Site;

/**
 * SiteSearch represents the model behind the search form about `common\models\Site`.
 */
class SiteSearch extends Site
{
    private $site_status_index = null;

    public function setSiteStatus($status) {
        $this->site_status_index = $status;
        return $this;
    }

    public function rules()
    {
        return [
            [['id', 'type_id', 'user_id', 'district_id', 'subject_id', 'status_index'], 'integer'],
            [['domain', 'title', 'created_timestamp', 'org_title', 'location', 'headname'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Site::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => 'queue_index asc',
            ],
        ]);

        $query->orderBy('queue_index asc');

        /*if(!\Yii::$app->user->can('admin')) {
            $query->andWhere('status_index = ' . Site::STATUS_ON_MODERATION);
        }*/
        if ($this->site_status_index) {
            $query->andWhere('status_index = ' . $this->site_status_index);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'status_index' => $this->status_index,
            'user_id' => $this->user_id,
            'created_timestamp' => $this->created_timestamp,
            'district_id' => $this->district_id,
            'subject_id' => $this->subject_id,
        ]);

        $query->andFilterWhere(['ilike', 'domain', $this->domain])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'org_title', $this->org_title])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'headname', $this->headname]);

        return $dataProvider;
    }
}

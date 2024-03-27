<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RateRequest;

/**
 * RateRequestSearch represents the model behind the search form about `common\models\RateRequest`.
 */
class RateRequestSearch extends RateRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site_id', 'period_id', 'status_index', 'expert_id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['score'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $rateRequestStatus = null)
    {
        $query = RateRequest::find();
        $query->with('site', 'site.type');
        $query->orderBy('expert_id, queue_index');

        if($rateRequestStatus) {
            if(is_array($rateRequestStatus)) {
                $query->andWhere(['in', 'status_index', $rateRequestStatus]);
            } else {
                $query->andWhere('status_index = :status_index', ['status_index' => $rateRequestStatus]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'site_id' => $this->site_id,
            'period_id' => $this->period_id,
            'status_index' => $this->status_index,
            'expert_id' => $this->expert_id,
            'created_datetime' => $this->created_datetime,
            'score' => $this->score,
        ]);

        return $dataProvider;
    }
}

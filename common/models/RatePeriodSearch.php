<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RatePeriod;

/**
 * RatePeriodSearch represents the model behind the search form about `common\models\RatePeriod`.
 */
class RatePeriodSearch extends RatePeriod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active_flag', 'list_order'], 'integer'],
            [['title', 'created_datetime'], 'safe'],
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
    public function search($params)
    {
        $query = RatePeriod::find();
        $query->orderBy('list_order desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['list_order' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active_flag' => $this->active_flag,
            'list_order' => $this->list_order,
            'created_datetime' => $this->created_datetime,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

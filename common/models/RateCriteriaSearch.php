<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RateCriteria;

/**
 * RateCriteriaSearch represents the model behind the search form about `common\models\RateCriteria`.
 */
class RateCriteriaSearch extends RateCriteria
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id'], 'integer'],
            [['title', 'created_datetime'], 'safe'],
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
    public function search($params)
    {
        $query = RateCriteria::find();

        if($this->type_id) {
            $query->andFilterWhere(['type_id' => $this->type_id]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            //'type_id' => $this->type_id,
            'score' => $this->score,
            'created_datetime' => $this->created_datetime,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

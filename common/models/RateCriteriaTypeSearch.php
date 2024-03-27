<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RateCriteriaType;

/**
 * RateCriteriaTypeSearch represents the model behind the search form about `common\models\RateCriteriaType`.
 */
class RateCriteriaTypeSearch extends RateCriteriaType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'site_type_id', 'period_id'], 'integer'],
            [['title'], 'safe'],
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
        $query = RateCriteriaType::find();
        $query->with('siteType');

        if($this->period_id) {
            $query->andFilterWhere(['period_id' => $this->period_id]);
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
            'site_type_id' => $this->site_type_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

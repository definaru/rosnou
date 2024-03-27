<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SiteSubject;

/**
 * SiteSubjectSearch represents the model behind the search form about `common\models\SiteSubject`.
 */
class SiteSubjectSearch extends SiteSubject
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title'], 'safe'],
            ['district_id', 'exist', 'targetClass' => SiteDistrict::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SiteSubject::find();

        $query->with('district');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'district_id' => $this->district_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}

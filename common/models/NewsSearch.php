<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * NewsSearch represents the model behind the search form about `common\models\News`.
 */
class NewsSearch extends News
{
    public function rules()
    {
        return [
            [['id', 'views_count'], 'integer'],
            [['title', 'slug', 'preview', 'content', 'publish_date', 'meta_title', 'meta_keywords', 'meta_description', 'created_at', 'updated_at'], 'safe'],
            [['is_published'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }



    public function search($params)
    {
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (isset($this->is_published) && $this->is_published) {
            $query->andFilterWhere([
                'is_published' => 1,
            ]);
        }
        $query->andFilterWhere([
            'like', 'title', $this->title]
        );

        return $dataProvider;
    }
}

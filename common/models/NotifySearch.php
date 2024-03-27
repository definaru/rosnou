<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notify;

/**
 * NotifySearch represents the model behind the search form about `common\models\Notify`.
 */
class NotifySearch extends Notify
{
    public function rules()
    {
        return [
            [['id', 'active_flag', 'sendemail_flag'], 'integer'],
            [['title', 'body', 'finish_datetime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Notify::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finish_datetime' => $this->finish_datetime,
            'active_flag' => $this->active_flag,
            'sendemail_flag' => $this->sendemail_flag,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}

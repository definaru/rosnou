<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $role = null;

    public function rules()
    {
        return [
            [['id','role'], 'integer'],
            [['email', 'firstname', 'middlename', 'lastname', 'orgname', 'position', 'password_hash', 'password_reset_token', 'created_timestamp', 'login', 'auth_key'], 'safe'],
            [['email_verified'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_timestamp' => $this->created_timestamp,
            'email_verified' => $this->email_verified,
        ]);

        if ($this->role) {
            switch ($this->role) {
                case 1:
                    $query->andFilterWhere(['is_admin' => 1]);
                    break;
                case 2:
                    $query->andFilterWhere(['is_expert' => 1]);
                    break;
                case 3:
                    $query->andFilterWhere(['is_moderator' => 1]);
                    break;
            }
        }
        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'middlename', $this->middlename])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'orgname', $this->orgname])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        return $dataProvider;
    }
}

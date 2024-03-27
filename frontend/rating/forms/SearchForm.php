<?php

namespace frontend\rating\forms;

use yii\base\Model;

class SearchForm extends Model
{
    /**
     * @var
     */
    public $query;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['query', 'string', 'length' => [3, 100]],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'query' => 'Запрос',
        ];
    }
}
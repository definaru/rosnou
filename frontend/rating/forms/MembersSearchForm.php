<?php

namespace frontend\rating\forms;

use yii\base\Model;

class MembersSearchForm extends Model
{
      public $site_type_id;
      public $district_id;
      public $subject_id;
      public $orderBy;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
          [['site_type_id', 'district_id', 'subject_id'], 'integer'],
          [['orderBy'], 'string'],

        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'site_type_id' => 'Категория сайта',
            'district_id' => 'Федеральный округ',
            'subject_id' => 'Субъект Федерации',
            'orderBy' => 'Порядок сортировки',
        ];
    }
}
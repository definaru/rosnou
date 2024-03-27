<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rate_period}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $active_flag
 * @property integer $list_order
 * @property string $created_datetime
 * @property request1_accept_flag
 * @property request2_accept_flag
 * @property register_accept_flag
 * @property finished_flag;
 * @property slug;
 *
 * @property RateRequest[] $rateRequests
 */
class RatePeriod extends \yii\db\ActiveRecord
{
    public static function find()
    {
        return new RatePeriodQuery(self::class);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_period}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','slug'], 'required'],
            [['slug'], 'unique'],
            [['title','diplom_folder','slug'], 'string'],
            [['active_flag', 'list_order', 'request1_accept_flag', 'request2_accept_flag', 'register_accept_flag', 'finished_flag'], 'integer'],
            [['created_datetime'], 'safe'],
        ];
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'active_flag' => 'Активность',
            'list_order' => 'Порядок',
            'created_datetime' => 'Дата создания',
            'request1_accept_flag' => 'Первичное самообследование',
            'request2_accept_flag' => 'Вторичное самообследование',
            'register_accept_flag' => 'Регистрация участников',
            'finished_flag' => 'Период завершен',
            'slug' => 'slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateRequests()
    {
        return $this->hasMany(RateRequest::className(), ['period_id' => 'id']);
    }

    /**
     * @return int
     */
    public function isActive()
    {
        return $this->active_flag;
    }
}

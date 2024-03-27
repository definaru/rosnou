<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_rate_criteria".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $title
 * @property string $score
 * @property string $created_datetime
 *
 * @property RateCriteriaType $type
 */
class RateCriteria extends \yii\db\ActiveRecord
{
    const CRITERIA_TYPE_YESNO = 1;
    const CRITERIA_TYPE_LIST = 2;

    const CRITERIA_FIELD_POST_COUNT = 'post_count';
    const CRITERIA_FIELD_MEMBER_COUNT = 'member_count';
    const CRITERIA_FIELD_ACTION_COUNT = 'action_count';

    const CRITERIA_FUNCTION_VK_GROUP_LINK = 'vk_group_link';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => false,
                'value' => function() {
                    return date('Y-m-d H:i:s');
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_criteria}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'title', 'score'], 'required'],
            [['type_id', 'field_type'], 'integer'],
            [['title','sysname', 'field_name', 'function'], 'string'],
            [['score'], 'number'],
            [['type_id'], 'exist', 'targetClass' => RateCriteriaType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип',
            'title' => 'Название',
            'score' => 'Баллы',
            'created_datetime' => 'Дата создания',
            'field_type' => 'Тип критерия',
            'field_name' => 'Название поля',
            'function' => 'Функция',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(RateCriteriaType::className(), ['id' => 'type_id']);
    }

    /*
    Типы критерия
    */
    public function getCriteriaTypeList() {
        return [
            self::CRITERIA_TYPE_YESNO => 'да/нет',
            self::CRITERIA_TYPE_LIST => 'список',
        ];
    }

    /*
    Список полей 
    */
    public function getCriteriaFieldList() {
        return [
            self::CRITERIA_FIELD_POST_COUNT => 'post_count', 
            self::CRITERIA_FIELD_MEMBER_COUNT => 'member_count', 
            self::CRITERIA_FIELD_ACTION_COUNT => 'action_count',
        ];
    }

    /*
    Список функций
    */
    public function getCriteriaFunctionList() {
        return [
            self::CRITERIA_FUNCTION_VK_GROUP_LINK => 'vk_group_link', 
        ];
    }
}

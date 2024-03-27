<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_criteria_result_comment".
 *
 * @property integer $id
 * @property string $body
 * @property integer $result_id
 * @property string $created_datetime
 * @property integer $user_id
 *
 * @property RateCriteriaResult $result
 * @property User $user
 */
class RateCriteriaResultComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_criteria_result_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['body', 'result_id', 'user_id'], 'required'],
            [['body'], 'string'],
            [['result_id', 'user_id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['result_id'], 'exist', 'skipOnError' => true, 'targetClass' => RateCriteriaResult::className(), 'targetAttribute' => ['result_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'body' => 'Body',
            'result_id' => 'Result ID',
            'created_datetime' => 'Created Datetime',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        return $this->hasOne(RateCriteriaResult::className(), ['id' => 'result_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

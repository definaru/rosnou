<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tbl_rate_criteria_result".
 *
 * @property integer $id
 * @property integer $request_id
 * @property integer $status_index
 * @property integer $criteria_id
 * @property string $created_datetime
 * @property string $url
 *
 * @property RateCriteria $criteria
 * @property RateRequest $request
 * @property RateCriteriaResultComment[] $rateCriteriaResultComments
 */
class RateCriteriaResult extends \yii\db\ActiveRecord
{
    const STATUS_YES = 1;
    const STATUS_NO = 2;

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
        return '{{%rate_criteria_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['request_id', 'criteria_id'], 'required'],
            [['request_id', 'status_index', 'criteria_id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['url'], 'string'],
            [['request_id', 'criteria_id'], 'unique', 'targetAttribute' => ['request_id', 'criteria_id'], 'message' => 'The combination of Request ID and Criteria ID has already been taken.'],
            [['criteria_id'], 'exist', 'skipOnError' => true, 'targetClass' => RateCriteria::className(), 'targetAttribute' => ['criteria_id' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => RateRequest::className(), 'targetAttribute' => ['request_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'request_id' => 'Request ID',
            'status_index' => 'Status Index',
            'criteria_id' => 'Criteria ID',
            'created_datetime' => 'Created Datetime',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriteria()
    {
        return $this->hasOne(RateCriteria::className(), ['id' => 'criteria_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(RateRequest::className(), ['id' => 'request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateCriteriaResultComments()
    {
        return $this->hasMany(RateCriteriaResultComment::className(), ['result_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function isYes()
    {
        return $this->status_index == self::STATUS_YES;
    }
}

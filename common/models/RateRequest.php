<?php

namespace common\models;

use domain\ModelSaveException;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%rate_request}}".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $period_id
 * @property integer $status_index
 * @property integer $expert_id
 * @property string $created_datetime
 * @property string $score
 *
 * @property RatePeriod $period
 * @property Site $site
 * @property User $expert
 */
class RateRequest extends \yii\db\ActiveRecord
{
    const STATUS_NOT_FINISHED = 0;
    const STATUS_FINISHED = 1;
    const STATUS_USER_DRAFT = 2;
    const STATUS_EXPERT_DRAFT = 3;
    const STATUS_CHECKED = 4;

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
     * @return RateRequestQuery
     */
    public static function find()
    {
        return new RateRequestQuery(self::class);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_request}}';
    }

    /**
     * @param Site $site
     * @param RatePeriod $period
     * @return static
     * @throws ModelSaveException
     */
    public static function createNewRequest(Site $site, RatePeriod $period)
    {
        $rateRequest = new static();
        $rateRequest->site_id = $site->id;
        $rateRequest->period_id = $period->id;
        $rateRequest->status_index = self::STATUS_NOT_FINISHED;

        if(!$rateRequest->save()) {
            throw new ModelSaveException($rateRequest);
        }

        return $rateRequest;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'period_id'], 'required'],
            [['site_id', 'period_id', 'status_index', 'expert_id'], 'integer'],
            [['created_datetime'], 'safe'],
            [['score'], 'number'],
            [['site_id', 'period_id'], 'unique', 'targetAttribute' => ['site_id', 'period_id'], 'message' => 'The combination of Site ID and Period ID has already been taken.'],
            ['status_index', 'in', 'range' => array_keys(self::statusOptions())],
            [['moderator_comment', 'user_comment'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * @param null $index
     * @return array|mixed|string
     */
    function getStatusIndexList($index = null)
    {
        $list = self::statusOptions();

        if( $index !== null ){
          return array_key_exists($index, $list) ? $list[$index] : '';
        }

        return $list;
    }

    /**
     * @return array|mixed|string
     */
    public function statusLabel()
    {
        return $this->getStatusIndexList($this->status_index);
    }
        
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Сайт',
            'period_id' => 'Период',
            'status_index' => 'Статус',
            'expert_id' => 'Эксперт',
            'created_datetime' => 'Дата подачи',
            'score' => 'Баллы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriod()
    {
        return $this->hasOne(RatePeriod::className(), ['id' => 'period_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(User::className(), ['id' => 'expert_id']);
    }

    /**
     * Description
     * @return type
     */
    public function getCriteriaResults()
    {
        return $this->hasMany(RateCriteriaResult::className(), ['request_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_NOT_FINISHED => 'не завершена',
            self::STATUS_FINISHED => 'самообследование завершено',
            self::STATUS_USER_DRAFT => 'проходит самообследование (черновик юзера)',
            self::STATUS_EXPERT_DRAFT => 'проходит экспертизу (черновик эксперта)',
            self::STATUS_CHECKED => 'экспертиза пройдена',
        ];
    }

    /**
     * @param int $status
     * @return bool
     */
    public static function validateStatus(int $status)
    {
        return in_array($status, array_keys(self::statusOptions()));
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status_index = $status;

        return $this;
    }

    /**
     * @param int|array $status
     * @return bool
     */
    public function isStatus($status)
    {
        if(is_array($status)) {
            return in_array($this->status_index, $status);
        }

        return $this->status_index == $status;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_rate_criteria_type".
 *
 * @property integer $id
 * @property string $title
 * @property integer $site_type_id
 * @property integer $period_id
 *
 * @property RateCriteria[] $rateCriterias
 * @property RatePeriod $period
 * @property SiteType $siteType
 */
class RateCriteriaType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_criteria_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'sysname'], 'string'],
            [['site_type_id', 'period_id', 'hidden_flag'], 'integer'],
            [['period_id'], 'exist', 'skipOnError' => false, 'targetClass' => RatePeriod::className(), 'targetAttribute' => ['period_id' => 'id']],
            [['site_type_id'], 'exist', 'skipOnError' => false, 'targetClass' => SiteType::className(), 'targetAttribute' => ['site_type_id' => 'id']],
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
            'site_type_id' => 'Тип сайта',
            'period_id' => 'Период',
            'hidden_flag' => 'Скрытый',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateCriterias()
    {
        return $this->hasMany(RateCriteria::className(), ['type_id' => 'id']);
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
    public function getSiteType()
    {
        return $this->hasOne(SiteType::className(), ['id' => 'site_type_id']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%rate_criteria_type_result}}".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $criteria_type_id
 * @property numeric $score
 *
 * @property RateCriteriaType $criteriaType
 * @property Site $site
 */
class RateCriteriaTypeResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rate_criteria_type_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score'], 'number'],
            [['site_id', 'criteria_type_id'], 'required'],
            [['site_id', 'criteria_type_id'], 'integer'],
            [['site_id', 'criteria_type_id'], 'unique', 'targetAttribute' => ['site_id', 'criteria_type_id'], 'message' => 'The combination of Site ID and Criteria Type ID has already been taken.'],
            [['criteria_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RateCriteriaType::className(), 'targetAttribute' => ['criteria_type_id' => 'id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id' => 'Site ID',
            'criteria_type_id' => 'Criteria Type ID',
            'score' => 'Score',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriteriaType()
    {
        return $this->hasOne(RateCriteriaType::className(), ['id' => 'criteria_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
}

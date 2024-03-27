<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%site_subject}}".
 *
 * @property integer $id
 * @property string $title
 *
 * @property Site[] $sites
 */
class SiteSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site_subject}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['map_code'], 'unique'],
            [['title'], 'required'],
            [['title','map_code'], 'string'],
            ['district_id', 'exist', 'targetClass' => SiteDistrict::class, 'targetAttribute' => 'id'],
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
            'district_id' => 'Федеральный округ',
            'map_code' => 'Код на карте'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasMany(Site::className(), ['subject_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(SiteDistrict::className(), ['id' => 'district_id']);
    }
}

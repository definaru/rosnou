<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%site_type}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug 
 *
 * @property Site[] $sites
 */
class SiteType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','slug'], 'required'],
            [['slug'], 'unique'],
            [['title', 'slug','diplom_file'], 'string'],
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
            'slug' => 'slug',
            'diplom_file' => 'Файл шаблона диплома'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSites()
    {
        return $this->hasMany(Site::className(), ['type_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function options()
    {
        return ArrayHelper::map(SiteType::find()->all(), 'id', 'title');
    }
}

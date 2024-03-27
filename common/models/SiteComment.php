<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbl_site_comment".
 *
 * @property integer $id
 * @property integer $site_id
 * @property integer $user_id
 * @property string $body
 * @property string $created_datetime
 * @property integer $site_status_index
 *
 * @property Site $site
 * @property User $user
 */
class SiteComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'user_id', 'body', 'site_status_index'], 'required'],
            [['site_id', 'user_id'], 'integer'],
            [['body'], 'string'],
            [['created_datetime'], 'safe'],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Site::className(), 'targetAttribute' => ['site_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['site_status_index', 'in', 'range' => array_keys((new Site())->statusOptions())],
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
            'user_id' => 'User ID',
            'body' => 'Body',
            'created_datetime' => 'Created Datetime',
            'site_status_index' => 'Site Status Index',
        ];
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

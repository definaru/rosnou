<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%site}}".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $domain
 * @property string $title
 * @property integer $status_index
 * @property integer $user_id
 * @property string $created_timestamp
 * @property string $org_title
 * @property integer $district_id
 * @property integer $subject_id
 * @property string $location
 * @property string $headname
 *
 * @property SiteDistrict $district
 * @property SiteSubject $subject
 * @property SiteType $type
 * @property User $user
 */
class Site extends \yii\db\ActiveRecord
{
    const STATUS_ON_MODERATION = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DENIED = 3;

    /**
     * @return SiteQuery
     */
    public static function find()
    {
        return new SiteQuery(Site::class);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%site}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'user_id', 'district_id', 'subject_id'], 'integer'],
            [['type_id', 'domain', 'title'], 'required'],
            [['domain', 'title', 'org_title', 'location', 'headname'], 'string'],
            [['created_timestamp'], 'safe'],

            ['domain', 'url'],
            ['domain', 'unique'],

            ['district_id', 'exist', 'targetClass' => SiteDistrict::class, 'targetAttribute' => 'id'],
            ['subject_id', 'exist', 'targetClass' => SiteSubject::class, 'targetAttribute' => 'id'],

            ['type_id', 'exist', 'skipOnEmpty' => false, 'targetClass' => SiteType::class, 'targetAttribute' => 'id'],

            ['headname_email', 'required'],
            ['headname_email', 'email'],

            ['have_ads', 'boolean', ],
            ['status_index', 'in', 'range' => array_keys($this->statusOptions())],
        ];
    }

    /**
     * @param null $index
     * @return array|mixed|string
     */
    function getStatusIndexList($index = null)
    {
        $list = $this->statusOptions();

        if( $index !== null ){
          return array_key_exists($index, $list) ? $list[$index] : '';
        }

        return $list;
    }

    /**
     * @param $status
     * @return bool
     */
    public function isStatus($status)
    {
        return is_array($status) ? in_array($this->status_index, $status) : $this->status_index == $status;
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
     * @return array
     */
    public function statusOptions()
    {
        return [
            self::STATUS_ON_MODERATION => 'На проверке',
            self::STATUS_APPROVED => 'Сайт проверен',
            self::STATUS_DENIED => 'Отклонен',
        ];
    }

    public function getStatusName(){
        $list = $this->statusOptions();
        return $list[$this->status_index];
    }
        
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Тип',
            'domain' => 'Домен',
            'title' => 'Название',
            'status_index' => 'Статус',
            'user_id' => 'Пользователь',
            'created_timestamp' => 'Дата создания',
            'org_title' => 'Название организации',
            'district_id' => 'Округа',
            'subject_id' => 'Субъект',
            'location' => 'Населенный пункт',
            'headname' => 'ФИО руководителя',
            'headname_email' => 'Email руководителя',
            'queue_index' => 'Очередь',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(RateRequest::className(), ['site_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getActiveRequest()
    {
        return $this->hasOne(RateRequest::className(), ['site_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(SiteDistrict::className(), ['id' => 'district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(SiteSubject::className(), ['id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(SiteType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

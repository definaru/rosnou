<?php

namespace frontend\rating\forms;

use common\models\Site;
use common\models\SiteType;
use yii\helpers\ArrayHelper;
use common\models\SiteSubject;
use common\models\SiteDistrict;

class SiteForm extends Site
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            ['district_id', 'exist', 'targetClass' => SiteDistrict::class, 'targetAttribute' => 'id'],
            ['subject_id', 'exist', 'targetClass' => SiteSubject::class, 'targetAttribute' => 'id'],
            ['type_id', 'exist', 'targetClass' => SiteType::class, 'targetAttribute' => 'id'],
            [['type_id','district_id', 'subject_id'], 'integer'],
            [['domain', 'title'], 'required'],
            [['title', 'org_title', 'location', 'headname'], 'string'],
            ['headname_email', 'required'],
            ['headname_email', 'email'],
            ['domain', 'url'],

            ['domain', 'uniqueDomain'],
            ['have_ads', 'boolean'],
        ];
    }


    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'id',
            'type_id',
            'domain',
            'title',
            'org_title',
            'district_id',
            'subject_id',
            'location',
            'headname',
            'headname_email',
            'have_ads',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type_id' => 'Категория сайта',
            'domain' => 'Адрес сайта',
            'title' => 'Название сайта',
            'org_title' => 'Официальное название образовательной организации',
            'location' => 'Населенный пункт',
            'headname' => 'ФИО директора ОУ',
            'headname_email' => 'Эл. почта директора/школы',
            'district_id' => 'Федеральный округ',
            'subject_id' => 'Субъект федерации',
            'have_ads' => 'На сайте есть реклама',
        ]);
    }

    public function uniqueDomain($attribute) {
        $domain = parse_url($this->$attribute, PHP_URL_HOST);

        if (!$this->isEdufaceDomain($domain)) {
            $url = parse_url($this->$attribute);
            $path = $url['path'] ?? '';
            $host = $url['host'] ?? '';
            $domain = trim($host.$path ?? '','/');
        }

        $query = Site::find();
        $query->where(['similar to', $attribute, '(http|https)://'.$domain.'%']);

        $Site = $query->one();
        
        if($Site && $this->id != $Site->id){
            $this->addError($attribute,"Сайт '{$this->$attribute}' уже существует");
        }
    }

    public function isEdufaceDomain($domain) {
        $check_domains = ['.eduface.ru', '.edumsko.ru', 'edusev.ru', 'educhel.ru', 'educrimea.ru'];

        foreach ($check_domains as $value) {
            if (strpos($domain, $value) !== FALSE) {
                return true;
            }
        }
        return false;
    }
    /**
     * @return array
     */
    public function typeOptions()
    {
        $options = SiteType::find()->orderBy('id')->all();

        return ArrayHelper::map($options, 'id', function($el) {
            return $el->title;
        });
    }

    /**
     * @return array
     */
    public function districtOptions()
    {
        $options = SiteDistrict::find()->orderBy('title')->all();

        return ArrayHelper::map($options, 'id', 'title');
    }

    /**
     * @return array
     */
    public function subjectOptions()
    {
        $options = SiteSubject::find()->orderBy('title')->all();

        return ArrayHelper::map($options, 'id', 'title');
    }
}
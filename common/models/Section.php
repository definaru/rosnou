<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%persona_section}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $controller
 * @property string $template
 * @property string $route
 * @property string $menu_route
 * @property integer $site_id
 * @property integer $menu_order
 * @property Site $site
 */
class Section extends \yii\db\ActiveRecord
{
    const DEFAULT_MODULE = 'default';

    public $sitemap = null;

    private $config = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['menu_order'], 'default', 'value' => 0],
            [['title', 'body', /*'template',*/ 'route', 'module', /*'menu_title'*/], 'string'],
            [[/*'site_id', /**'menu_order'*/ /*'menu_flag'*/ 'parent_id'], 'integer'],
            [['route'], 'unique'],
            ['parent_id','compare','compareAttribute' => 'id', 'operator' =>'!=','enableClientValidation' => false],
        ];
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_id' => 'Родительская секция',
            'route' => ' Полный абсолютный path страницы',
            'module' => 'Модуль',
            'title' => 'Название страницы',
            'menu_title' => 'Название страницы в меню',
            'body' => 'Содержимое страницы',
            'menu_flag' => 'Показывать в меню',
            'menu_order' => 'Позиция в меню',
            'template' => 'Название позиции в меню',
        ];
    }

    /**
     * Извлекаем список идентификаторов дочерних разделов
     */
    public function getChildrenId()
    {
        $list = $this->getChildren();
        $ids = [];
        foreach ($list as $item) {
            $ids[] = $item->id;
        }

        return $ids;
    }

    /**
     * Извлекаем данные о дочерних разделах из бд
     */
    public function getChildren()
    {
        $Site = Yii::$app->factory->site;
        $Sitemap = Yii::$app->factory->sitemap;
        $tree = $Sitemap->getSectionTree($this->route);

        $childrens = isset($tree[0]['children']) && sizeof($tree[0]['children']) > 0
            ? $tree[0]['children']
            : [];

        $routeList = [];

        foreach ($childrens as $item) {
            $routeList[] = $item['route'];
        }

        $childrens = self::find()
            ->where(['site_id' => $Site->id])
            ->andWhere(['in', 'route', $routeList])
            ->all();

        return $childrens;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }

    public function getConfig($name)
    {

        $Sitemap = $this->site->sitemap;
        $config = $Sitemap->getRouteConfig($this->route);

        return isset($config[$name]) ? $config[$name] : null;
    }

    /**
     * Извлекаем путь до директории в файловой структуре
     */
    public function getUploadRoot()
    {
        $webroot = $this->site->getWebRoot();
        $webfolder = $this->getFolder();

        return $webroot . $webfolder;
    }

    public function getPathWay()
    {
        return [];
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }
}

<?php

namespace frontend\rating\managers;

use common\models\Section;
use yii\helpers\ArrayHelper;

class MenuManager
{
    static private $sections = null;

    private $Site = null;

    private $User = null;

    private $Request = null;

    //public function __construct($Site, $User, $Request)
    //{
    //    $this->Site = $Site;
    //    $this->User = $User;
    //    $this->Request = $Request;
    //}

    public function getMenu($menu_only = true)
    {
        $sections = $this->getSections();

        foreach ($sections as $id => $section) {
            $parent_id = intval($section['parent_id']);

            if ($parent_id > 0) {
                $sections[$parent_id]['list'][] =& $sections[$id];
            }
        }

        return $sections;
    }

    /**
     * @return array
     */
    public function getRootSections()
    {
        $rootSections = [];

        foreach ($this->getSections() as $id => $section) {
            if (!$section['parent_id']) {
                $rootSections[] = $section;
            }
        }

        return $rootSections;
    }

    public function getTree($parentSection = null, $menu_only = true)
    {
        if ($parentSection === null) {
            return $this->getRootSections();
        }

        $sections = $this->getSections();

        $foundSection = null;
        foreach ($sections as $id => $section) {
            $parent_id = intval($section['parent_id']);

            // сборка дерева
            if ($parent_id > 0) {
                $sections[$parent_id]['list'][] =& $sections[$id];
            }

            // запоминаем id искомого подраздела
            if ($section['id'] == $parentSection->id) {
                $foundSection =& $sections[$id];
            }
        }

        return $foundSection['list'] ?? [];
    }

    public function getSlimTree($menu_only = true)
    {
        $sectionList = $this->getMenu($menu_only);
        $slimList = [];

        foreach ($sectionList as $section) {
            $section['level'] = 0;
            $slimList[] = $section;

            $list = $section['list'] ?? [];

            foreach ($list as $section2) {
                $section2['level'] = 1;
                $slimList[] = $section2;
            }
        }

        return $slimList;
    }

    public function getPathway(Section $section)
    {
        $sections = $this->getSections();

        $stackSection = $sections[$section->id] ?? null;

        $stack = [];
        while ($stackSection) {
            array_unshift($stack, $stackSection);
            $stackSection = $stackSection['parent_id'] > 0
                ? $sections[$stackSection['parent_id']]
                : null;
        }

        return $stack;
    }

    public function getSections()
    {
        if (self::$sections === null) {
            $sections = Section::find()
                ->select([
                    'id',
                    //'menu_flag',
                    //'template',
                    'parent_id',
                    'route',
                    'title',
                    //'menu_title',
                    'module'
                ])
                //->where(['trash_flag' => 0])
                ->orderBy('id')
                ->createCommand()
                ->queryAll();

            self::$sections = ArrayHelper::index($sections, 'id');
        }

        return self::$sections;
    }

    /**
     * @param $sections
     * @return array
     */
    public function tree()
    {
        $rootSections = [];
        $childSections = [];

        foreach($this->getSections() as $section) {
            if($section['parent_id']) {
                $childSections[$section['parent_id']][] = $section;
            } else {
                $rootSections[] = $section;
            }
        }

        return [
            'root' => $rootSections,
            'childs' => $childSections,
        ];
    }

    public function getList($tree)
    {
        $list = [];

        foreach($tree['root'] as $section) {
            $list[$section['id']] = $section;

            if(isset($tree['childs'][$section['id']])) {
                //$list[$section['id']]['childs'] =
            }
        }
    }
}

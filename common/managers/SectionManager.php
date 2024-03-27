<?php

namespace common\managers;

use Yii;
use common\models\Section;
use common\components\SectionUrlRule;

class SectionManager
{
    /**
     * @var
     */
    private $user;

    /**
     * @var
     */
    private $currentSection;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param SectionUrlRule $Section
     */
    public function setCurrentSection(SectionUrlRule $Section)
    {
        $this->currentSection = $Section;
    }

    /**
     * @return mixed
     */
    public function getCurrentSection()
    {
        return $this->currentSection;
    }

    /**
     * @param $module
     * @return array
     * @throws \Exception
     */
    public function getSectionsByModule($module)
    {
        $manager = Yii::$app->urlManager;
        $rules = $manager->rules;
        $sections = [];

        foreach ($rules as $rule) {
            if (property_exists($rule, 'object') && $rule->object->module == $module) {
                $sections[] = $rule->object;
            }
        }

        if (!$sections) {
            throw new \Exception("Section with module '{$module}' not found");
        }

        return $sections;
    }

    /**
     * @param Section $Section
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSectionChildren(Section $Section)
    {
        $Sections = Section::find()->where(['parent_id' => $Section->id])->all();

        return $Sections;
    }

    /**
     * @param $id
     * @return bool
     */
    public function getSectionById($id)
    {
        $manager = Yii::$app->urlManager;
        $rules = $manager->rules;

        foreach ($rules as $rule) {
            if (property_exists($rule, 'object') && $rule->object->id == $id) {
                return $rule->object;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getBreadCrumbs()
    {
        $currentSection = $this->getcurrentSection();

        if(!$currentSection) {
            return [];
        }

        $section = $currentSection->object;
        $sections = $this->getBreadcrumbSections($section);
        krsort($sections);

        return array_values($sections);
    }

    /**
     * @param $section
     * @param array $sections
     * @return array
     */
    public function getBreadcrumbSections($section, $sections = [])
    {
        if (!empty($section)) {
            $sections[] = $section;
            if ($section['parent_id'] > 0) {
                $parent = $this->getSectionById($section->parent_id);

                return $this->getBreadcrumbSections($parent, $sections);
            }
        }

        return $sections;
    }

    /**
     * @param $module
     * @return mixed
     * @throws \Exception
     */
    public function getSectionByModule($module)
    {
        $manager = Yii::$app->urlManager;
        $rules = $manager->rules;

        foreach ($rules as $rule) {
            if (property_exists($rule, 'object') && $rule->object->module == $module) {
                return $rule;
            }
        }

        throw new \Exception("Section with module '{$module}' not found");
    }

    /**
     * @param $prefix
     * @return mixed
     * @throws \Exception
     */
    public function getSection($prefix)
    {
        $manager = Yii::$app->urlManager;
        $rules = $manager->rules;

        foreach ($rules as $rule) {
            if ($rule instanceof SectionUrlRule) {
                if ($rule->prefix == $prefix) {
                    return $rule;
                }
            }
        }

        throw new \Exception("Section with prefix '{$prefix}' not found");
    }

    /**
     * Генерим стартовый набор секций для нового сайта
     */
    public function buildSections($SitesQuery, $_skeleton)
    {
        $route_key = 'route';
        $logger = [];
        $skeleton = [];

        // Делаем плоский массив из 2х уровней
        // на основе роутинга
        // чтобы можно было получить данные секции по ключу (route)
        // TODO: сделать рекурсивную обработку
        // сейчас при создании элементов создаются только 3 уровня вложенности
        foreach ($_skeleton as $order1 => $item) {

            $route = $item[$route_key];

            $skeleton[$route] = $item;
            $skeleton[$route]['parent'] = null;

            // Если порядок не задан в св-вах - берем порядок элемента в массиве
            $skeleton[$route]['menu_order'] = $skeleton[$route]['menu_order'] ?? $order1;

            // второй уровень
            if (isset($item['list'])) {
                foreach ($item['list'] as $order2 => $second) {

                    $second_route = $second[$route_key];
                    $skeleton[$second_route] = $second;
                    $skeleton[$second_route]['parent'] = $route;

                    // Если порядок не задан в св-вах - берем порядок элемента в массиве
                    $skeleton[$second_route]['menu_order'] = $second['menu_order'] ?? $order2;

                    // третий уровень
                    if (isset($second['list'])) {
                        foreach ($second['list'] as $order3 => $third) {

                            $third_route = $third[$route_key];
                            $skeleton[$third_route] = $third;
                            $skeleton[$third_route]['parent'] = $second_route;

                            // Если порядок не задан в св-вах - берем порядок элемента в массиве
                            $skeleton[$third_route]['menu_order'] = $third['menu_order'] ?? $order3;
                        }
                    }
                }
            }
        }

        $index = 0;

        // Проходим по списку сайтов
        foreach ($SitesQuery->each() as $Site) {

            $Sections = Section::find()
                ->where(['site_id' => $Site->id])
                ->indexBy($route_key)
                ->all();

            // Создаем не достающие секции в бд и запоминаем в $Sections
            foreach ($skeleton as $route => $item) {
                if (!isset($Sections[$route])) {
                    $Section = new Section();

                    $Section->site_id = $Site->id;
                    $Section->route = $item['route'];
                    $Section->menu_route = $item['menu_route'] ?? null;

                    $logger[] = "Create section '{$Section->title}' with route '{$route}'";

                    if (!$Section->save()) {
                        echo 'Section create. ';
                        print_r($Section->getErrors());
                        die;
                    }

                    $Sections[$route] = $Section;
                }
            }

            // Обновляем module и parent_id секций
            foreach ($Sections as $route => $Section) {

                if (!isset($skeleton[$route])) {
                    $logger[] = "!!! Route '{$route}' not exists in skeleton file";
                    continue;
                }

                $item = $skeleton[$route];

                $Section->title = $item['title'];
                $Section->route = $item['route'];
                $Section->module = $item['module'] ?? null;
                $Section->menu_order = $item['menu_order'] ?? intval($menu_order);
                $Section->menu_flag = $item['menu'] ? 1 : 0;
                $Section->menu_title = $item['menu_title'] ?? null;
                $Section->menu_route = $item['menu_route'] ?? null;
                $Section->template = $item['template'] ?? null;

                if (!empty($item['parent']) && isset($sectionIds[$route])) {
                    $Section->parent_id = $sectionIds[$route];
                }

                $logger[] = "Module '{$Section->module}' for route '{$route}'";

                // выставляем parent_id
                if ($skeleton[$route]['parent']) {
                    $Section->parent_id = $Sections[$skeleton[$route]['parent']]['id'];
                }

                if (!$Section->save()) {
                    echo 'Section save all attrs. ';
                    print_r($Section->getErrors());
                    die;
                }
            }
        }

        return $logger;
    }
}

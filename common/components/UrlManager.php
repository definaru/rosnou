<?php

namespace common\components;

use Yii;
use common\models\Section;

class UrlManager extends \yii\web\UrlManager
{
    public $moduleRules;

    public $modules = false;

    public function init()
    {
        parent::init();

        if ($this->modules !== false) {
            $this->buildModuleRules();
        }
    }

    public function buildModuleRules()
    {
        $sections = Section::find()
            ->alias('s')
            ->where([
                //'s.site_id' => Yii::$app->factory->site->id,
                //'s.trash_flag' => 0,
            ])
            //->orderBy('menu_order')
            ->all();

        // Поднимаем правила, в которых присутствует фрагмент текущего роутинга вверх
        // сделано для корректной работы автоформирования url
        // поскольку yii2 берет первый по порядку походящий роутинг из
        // массива правил
        $pathinfo = '/' . Yii::$app->request->pathinfo;

        usort($sections, function ($a, $b) use ($pathinfo) {
            return $this->samesymbols($b['route'], $pathinfo) <=> $this->samesymbols($a['route'], $pathinfo);
        });

        $commonRules = [];
        foreach ($sections as $section) {
            $module = $section->module ?: 'default';

            $rule = [
                'class' => \common\components\SectionUrlRule::class,
                'title' => $section['title'],
                'prefix' => trim($section['route'], '/'),
                'rules' => $this->modules[$module]['rules'],
                //'template' => $this->modules[$module]['template'],
                'object' => $section,
                //'folder' => isset($this->modules[$module]['folder'])
                //    ? $this->modules[$module]['folder']
                //    : '/section/' . $section->id,

                'params' => $this->modules[$module]['params'] ?? [],
            ];

            $commonRules[] = $rule;
        }

        $this->addRules($commonRules, false);
    }

    /**
     * Функция сравнивает на сколько совпадают начала 2х строк
     */
    public function samesymbols($value1, $value2)
    {
        $counter = 0;

        while (isset($value1[$counter])
            && isset($value2[$counter])
            && $value1[$counter] == $value2[$counter]) {
            $counter++;
        }

        return $counter;
    }
}

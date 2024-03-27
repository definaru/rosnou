<?php

namespace common\components;

use common\models\Section;
use yii\helpers\ArrayHelper;

class SectionsTree
{
    public $sections;

    public function __construct()
    {
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

        $this->sections = ArrayHelper::index($sections, 'id');
    }

    /**
     * @return array
     */
    public function getTree($exceptID = null)
    {
        return $this->buildTree($this->sections);
    }

    /**
     * @param array $categories
     * @param int $categoryId
     * @param int $level
     * @return array
     */
    private function buildTree(array $categories, $categoryId = 0, $level = 0)
    {
        $tree = [];

        foreach($categories as $category) {
            if($category['parent_id'] == $categoryId) {
                $category['level'] = $level;

                $tree[$categoryId][$category['id']] = [
                    'item' => $category,
                    'childs' => $this->buildTree($categories, $category['id'], $level + 1),
                ];
            }
        }

        return $tree;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getList($prefix = '-')
    {
        $tree = $this->getTree();

        return $this->buildList($prefix, [], $tree);
    }

    /**
     * @param $prefix
     * @param array $list
     * @param array $tree
     * @param int $categoryId
     * @param int $level
     * @return array
     */
    private function buildList($prefix, array $list, array $tree, $categoryId = 0, $level = 0)
    {
        $list = ['Не определено'];

        if ($tree) {
            foreach ($tree[$categoryId] as $node) {
                $list[$node['item']['id']] = str_repeat($prefix, $level) . ' '
                    . $node['item']['title'];

                if ($node['childs']) {
                    $list = $list + $this->buildList($prefix, $list,
                            $node['childs'], $node['item']['id'], $level + 1);
                }
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    public function options()
    {
        $tree = $this->getTree();

        return $this->buildOptions($tree);
    }

    /**
     * @param array $tree
     * @param array $list
     * @param int $id
     * @param int $level
     * @return array
     */
    private function buildOptions(array $tree, $id = 0, $level = 0)
    {
        $list = [];

        if (isset($tree[$id])) {
            foreach ($tree[$id] as $node) {
                $list[] = $node['item'];

                if ($node['childs']) {
                    $list = array_merge($list, $this->buildOptions($node['childs'], $node['item']['id'], $level + 1));
                }
            }
        }

        return $list;
    }
}
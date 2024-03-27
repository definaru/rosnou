<?php

namespace backend\rating\controllers\tree;

use common\components\SectionsTree;
use common\models\Section;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use backend\edudep\forms;
use common\models\Site;
use frontend\rating\managers\MenuManager;
use yii\web\Controller;

class DefaultController extends Controller
{
    protected $post_item;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $parent_id = Yii::$app->request->get('parent_id');
        $parentSection = $parent_id > 0 ? Section::findOne($parent_id) : null;

        $menuManager = new MenuManager();

        $tree = (new SectionsTree())->options();

        $parent = $parentSection ? $parentSection->parent : null;
        $pathway = $parent ? $menuManager->getPathway($parent) : [];

        return $this->render('index', [
            'tree' => $tree,
            'pathway' => $pathway,
            'section' => $parentSection,
        ]);
    }

    public function actionDetail($id)
    {
        $errors = [];
        $post = Yii::$app->request->post();

        $model = Section::findOne($id);

        $ParentSection = $model->parent;
        $parent_title = $ParentSection ? $ParentSection->title : 'Главная';

        $sections = (new SectionsTree())->getList();

        if (isset($post['save'])) {
            $model->load($post);
            $model->save();
            $errors = $model->getErrors();

            if (!$errors) {
                $this->refresh();
            }
        }

        // получаем дерево
        $MenuManager = new MenuManager();
        $parents = $ParentSection ? $MenuManager->getPathway($ParentSection) : [];

        return $this->render('detail', [
            'errors' => $errors,
            'model' => $model,
            'parents' => $parents,
            'sections' => $sections,
            'parent_title' => $parent_title,
            'modules' => $this->getSectionModules(),
        ]);
    }

    public function actionAdd()
    {
        $parent_id = Yii::$app->request->get('parent_id');
        $parent_title = $parent_id > 0 ? Section::findOne($parent_id)->title : 'Главная';

        $menuManager = new MenuManager();
        $parentSection = $parent_id > 0 ? Section::findOne($parent_id) : null;
        $parents = $parentSection ? $menuManager->getPathway($parentSection) : [];

        $model = new Section();
        $model->module = \common\models\Section::DEFAULT_MODULE;

        $post = \Yii::$app->request->post();
        $errors = [];
        if (isset($post['save'])) {
            $model->load($post);
            $model->save();
            $errors = $model->getErrors();

            if (!$errors) {
                return $this->redirect(Url::to(['tree/default/detail', 'id' => $model->id]));
            }
        }

        $model->parent_id = $parent_id;

        return $this->render('add', [
            'errors' => $errors,
            'model' => $model,
            'parent_title' => $parent_title,
            'modules' => $this->getSectionModules(),
            'sections' => (new SectionsTree())->getList(' - '),
            'parents' => $parents,
        ]);
    }

    public function actionDelete($id)
    {
        $model = Section::findOne($id);
        $model->delete();

        return $this->redirect(Url::to(['tree/default/detail', 'id' => $model->id]));
    }

    /**
     * Список модулей для edudep
     */
    public function getSectionModules()
    {
        $modules = require(Yii::getAlias('@frontend/rating/config/modules.php'));

        $moduleList = [];
        foreach ($modules as $key => $module) {
            $moduleList[$key] = $module['name'];
        }

        return $moduleList;
    }

    public function actionEnable($id)
    {
        $model = Section::findOne($id);
        $model->trash_flag = 0;
        $model->save();

        return $this->redirect(Url::to(['tree/default/detail', 'id' => $model->id]));
    }

    public function getSections($parentId = null)
    {
        $MenuManager = new MenuManager();
        $slimTree = (new SectionsTree())->getList();

        $slimTree = $parentId ? $slimTree[$parentId] : $slimTree;

        $sections = [];
        foreach ($slimTree as $section) {
            $sections[$section['id']] = str_repeat('-', $section['level']) . ' ' . $section['title'];
        }

        return $sections;
    }
}

<?php

namespace frontend\rating\controllers;

use common\managers\SectionManager;
use frontend\rating\components\Controller;

class ExpertsController extends Controller
{
    public $layout = 'page';

    public function actionList()
    {
        $pageName = \Yii::$container->get(SectionManager::class)->getCurrentSection()->title;

        return $this->render('list', [
            'experts' => $this->experts(),
            'pageName' => $pageName,
        ]);
    }

    public function experts()
    {
        return [];
    }
}
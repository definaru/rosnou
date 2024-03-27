<?php

namespace frontend\rating\controllers;

use frontend\rating\components\Controller;
use frontend\rating\managers\MapManager;
use common\managers\SectionManager;

class MapController extends Controller
{
    /**
     * @return string
     */
    public function actionContent()
    {

    	$list = \Yii::$container->get(MapManager::class)->loadSubjectList();

    	return json_encode($list['list']);
    }

    public function actionShow()
    {
        $this->layout = 'main';
        $Section = \Yii::$container->get(SectionManager::class)->getCurrentSection()->object;
        $pageName =$Section->title;
        $list = \Yii::$container->get(MapManager::class)->loadSubjectList();

        return $this->render('show',[
            'list' => $list,
            'section' => $Section,
        ]);
    }
}
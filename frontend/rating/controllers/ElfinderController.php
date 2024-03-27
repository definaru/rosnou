<?php

namespace frontend\rating\controllers;

use Yii;
use yii\filters\AccessControl;
use common\components\ElfinderUtils;

/**
 * Site controller
 */
class ElfinderController extends \yii\web\Controller
{
    public $layout = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['xmanager'],
                        'allow' => true,
                        //'roles' => ['?']
                    ],
                ],
            ]
        ];
    }

    /**
     * Подгрузка справочников
     * @param string $modelName
     * @param string $methodName название метода который возвращает справочник
     *                           в виде [{value: id, text: title}]
     * @return null
     */
    //public function actionIndex() {
    //    return $this->render('index');
    //}

    /**
     * Делаем редирект с подстановкой папки и выбранного файла
     */
    public function actionXmanager(){
        $url = ElfinderUtils::buildUrl(Yii::$app->request);
        $this->redirect($url);
    }
}

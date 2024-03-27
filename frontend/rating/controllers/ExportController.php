<?php

namespace frontend\rating\controllers;

use yii\web\NotFoundHttpException;
use frontend\rating\components\Controller;
use frontend\rating\managers\ExportManager;

class ExportController extends Controller
{
    /**
     * @param int $countryDistrict
     */

    public function actionLoadSitesData()
    {
        $ExportManager = \Yii::$container->get('ExportManager');

        $limit = \Yii::$app->request->get('limit', 10);
        $offset = \Yii::$app->request->get('offset', 0);

        $json = $ExportManager->loadSitesDataJson($limit, $offset);

        echo $json;
    }

    public function actionLoadRatingData(String $domain)
    {
        $ExportManager = \Yii::$container->get(ExportManager::class);
        $json = $ExportManager->loadRatingDataJson($domain);
        echo $json;
    }

    public function actionLoadPeriodsData()
    {
        $PeriodManager = \Yii::$container->get('PeriodManager');
        $periods = $PeriodManager->loadPeriodsList();
        $list['periods'] = $periods;
        $json = json_encode($list,  JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo $json;
    }
}
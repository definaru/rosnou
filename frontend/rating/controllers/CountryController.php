<?php

namespace frontend\rating\controllers;

use common\models\SiteDistrict;
use common\models\SiteSubject;
use frontend\rating\components\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CountryController extends Controller
{
    /**
     * @param int $countryDistrict
     */
    public function actionSubjects(int $district)
    {
        $district = SiteDistrict::find()->where('id = :id', ['id' => $district])->one();

        if(!$district) {
            throw new NotFoundHttpException();
        }

        $subjects = SiteSubject::find()
            ->where('district_id = :district_id', ['district_id' => $district->id])
            ->orderBy('title ASC')
            ->all();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return ArrayHelper::map($subjects, 'id', 'title');
    }
}
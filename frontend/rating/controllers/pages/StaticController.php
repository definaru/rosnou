<?php

namespace frontend\rating\controllers\pages;

use common\models\RateCriteria;
use common\models\RateCriteriaType;
use common\models\RatePeriod;
use common\models\Section;
use common\models\SiteType;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use frontend\rating\components\Controller;
use frontend\rating\managers\MapManager;

class StaticController extends Controller
{
    public $layout = 'page';

    public function actionHome()
    {
        throw new \yii\web\NotFoundHttpException();
    }

    public function actionCriteria()
    {
        $pageName = 'Критерии';

        /** @var RatePeriod $activePeriod */
        if(!$activePeriod = RatePeriod::find()->active()->one()) {
            throw new NotFoundHttpException();
        }

        $siteTypes = SiteType::find()->orderBy('title')->all();

        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id', [
                'period_id' => $activePeriod->id,
            ])->orderBy('title')
            ->all();

        $criteriaTypesIds = ArrayHelper::map($criteriaTypes, 'id', 'id');
        $criteriaTypes = ArrayHelper::index($criteriaTypes, null, 'site_type_id');

        $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', $criteriaTypesIds])
            ->orderBy('title')
            ->all();
        $criterias = ArrayHelper::index($criteriasItems, null, 'type_id');

        return $this->render('criteria', compact(
            'pageName',
            'siteTypes',
            'criteriaTypes',
            'criterias'
        ));
    }

    public function actionPolicyCookie()
    {
        $pageName = 'Политика конфиденциальности сookie';

        return $this->render('cookie', [
            'breadcrumbs' => [$pageName],
            'pageName' => $pageName,
        ]);
    }

    public function actionContacts()
    {
        $pageName = 'Контакты';

        return $this->render('contacts', [
            'breadcrumbs' => [$pageName],
            'pageName' => $pageName,
        ]);
    }

    public function actionShow()
    {
        $section = Section::findOne(['route' => '/' . trim(\Yii::$app->request->pathInfo, '/')]);

        if(!$section) {
            throw new NotFoundHttpException();
        }

        return $this->render('show', [
            'pageName' => $section->title,
            'section' => $section,
        ]);
    }
}

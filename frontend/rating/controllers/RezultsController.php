<?php

namespace frontend\rating\controllers;

use common\managers\SectionManager;
use frontend\rating\components\Controller;

use frontend\rating\managers\MemberManager;
use frontend\rating\managers\PeriodManager;
use frontend\rating\managers\ScoreRulesManager;
use frontend\rating\managers\SiteTypeManager;
use frontend\rating\managers\DistrictManager;
use frontend\rating\managers\SubjectManager;
use frontend\rating\forms\MembersSearchForm;

class RezultsController extends Controller
{
    public $layout = 'page';

    public function actionList()
    {
        $pageName = \Yii::$container->get(SectionManager::class)->getCurrentSection()->title;

        $siteTypes_list = \Yii::$container->get(SiteTypeManager::class)->loadSiteTypesListSlug();
        $list =  \Yii::$container->get(PeriodManager::class)->loadFinishedPeriods();

        return $this->render('list', [
            'list' => $list,
            'pageName' => $pageName,
            'siteTypes_list' => $siteTypes_list,
        ]);
    }

    public function actionView($periodSlug, $typeSlug) {

        $pageName = \Yii::$container->get(SectionManager::class)->getCurrentSection()->title;

        $PeriodManager = \Yii::$container->get(PeriodManager::class);
        $ScoreRulesManager = \Yii::$container->get(ScoreRulesManager::class);
        $period = $PeriodManager->getPeriodBySlug($periodSlug);

        $list = $PeriodManager->loadPeriodSitesSlug($periodSlug, $typeSlug);
        $test =  \Yii::$container->get(PeriodManager::class)->loadFinishedPeriods();
        // Извлекаем правила отображения баллов
        $scoreRules = $ScoreRulesManager->getRules($list);

        return $this->render('view', [
            'pageName' => $pageName,
            'test' => $test,
            'list' => $list,
            'period_id' =>$period->id,
            'scoreRules' => $scoreRules,
        ]);
    }

    public function actionRedirect2016($id) {
        return $this->redirect("/rezults/leto-2016/{$id}cat/", 301);
    }
}

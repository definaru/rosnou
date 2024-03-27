<?php

namespace frontend\rating\controllers;

use common\managers\SectionManager;
use frontend\rating\components\Controller;

use frontend\rating\managers\MemberManager;
use frontend\rating\managers\PeriodManager;
use frontend\rating\managers\DiplomManager;
use frontend\rating\managers\ScoreRulesManager;
use frontend\rating\managers\SiteTypeManager;
use frontend\rating\managers\DistrictManager;
use frontend\rating\managers\SubjectManager;
use frontend\rating\forms\MembersSearchForm;
use frontend\rating\components\Exception;
use common\models\Site;

class UchastnikiController extends Controller
{
    public $layout = 'page';

    public function actionList()
    {
        $this->layout = 'page_uchastniki';
        
        $CurrentSection = \Yii::$container->get(SectionManager::class)->getCurrentSection()->object;
        $pageName = $CurrentSection->title;

        $form = new MembersSearchForm;
        $form->attributes = \Yii::$app->request->get('MembersSearchForm');
        $order = \Yii::$app->request->get('order', null);
        if ($order) {
           $form->orderBy = $order;
        }
        
        $MemberManager = \Yii::$container->get(MemberManager::class);
        $list = $MemberManager->search($form);

        $siteTypes_list = \Yii::$container->get(SiteTypeManager::class)->loadSiteTypesList();
        $siteDistrict_list = \Yii::$container->get(DistrictManager::class)->loadDistrictList();
        $siteSubject_list = \Yii::$container->get(SubjectManager::class)->loadSubjectList();


        return $this->render('list', [
            'list' => $list,
            'pageName' => $pageName,
            'section' => $CurrentSection,
            'order' => $order,
            'siteTypes_list' => $siteTypes_list,
            'siteDistrict_list' => $siteDistrict_list,
            'siteSubject_list' => $siteSubject_list,
            'model' => $form,
        ]);
    }

    public function actionView($id) {

        $pageName = \Yii::$container->get(SectionManager::class)->getCurrentSection()->title;
        $ScoreRulesManager = \Yii::$container->get(ScoreRulesManager::class);

        $periodID = \Yii::$app->request->get('period', null);

        if (!$periodID) {
            $periodID = \Yii::$app->request->post('PERIOD_ID') ?? false;
        }

        $MemberManager = \Yii::$container->get(MemberManager::class);

        $PeriodManager = \Yii::$container->get(PeriodManager::class);

        $Site = $MemberManager->loadSiteData($id);
        $periods = $PeriodManager->loadPeriodsData();
        $periods_finished = $PeriodManager->loadFinishedPeriods();

        $request_finished = $PeriodManager->loadFinishedRequests($Site,true);

        $ScoreData = [];
        if( isset($request_finished[0]) ){
            $ScoreData = $ScoreRulesManager->getScoreData($Site->type->score_type_id, $request_finished[0]->score);
        }

        if (!$periodID) {
            $periodID = array_keys($periods)[0];
        }

        $Period = $PeriodManager->getPeriod($periodID);

        $list = $PeriodManager->loadPeriodData($Period, $Site);

        return $this->render('view', [
            'pageName' => $pageName,
            'Site' => $Site,
            'periods' => $periods,
            'periods_finished' => $periods_finished,
            'request_finished' => $request_finished,
            'periodID' => $periodID,
            'list' => $list,
            'Period' => $Period,
            'ScoreData' => $ScoreData,
        ]);
    }
    public function actionDiplom($site_id, $period_slug) {

        $MemberManager = \Yii::$container->get(MemberManager::class);
        $PeriodManager = \Yii::$container->get(PeriodManager::class);
        $ScoreRulesManager = \Yii::$container->get(ScoreRulesManager::class);


        $Site = $MemberManager->loadSiteData($site_id);
        $Period = $PeriodManager->getPeriodBySlug($period_slug);
        $MaxScore = $ScoreRulesManager->getMaxScore($Period, $Site->type->id);

        $Request = $PeriodManager->loadFinishedRequests($Site,true, $Period->id);
        $ScoreData = $ScoreRulesManager->getScoreData($Site->type->score_type_id, $Request[0]->score);

        if (!$Request) {
            throw new \Exception('Диплом для данного периода отсутствует!');
        }

        $diplom =  \Yii::$container->get(DiplomManager::class)->getDiplom($Site, $Period, $Request[0], $MaxScore, $ScoreData);

        return \Yii::$app->response->sendFile($diplom['file'], $diplom['name'], ['inline'=>true]);
    }
}
<?php

namespace frontend\rating\controllers\sites;

use yii;

use common\commands\site\SaveSiteByModeratorCommand;
use common\models\RateCriteria;
use common\models\RateCriteriaType;
use common\models\Site;
use common\models\RatePeriod;
use common\models\RateRequest;
use common\models\SiteComment;
use common\models\SiteSearch;
use common\models\SiteType;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use frontend\rating\forms\SiteForm;
use common\commands\site\SaveSiteCommand;
use frontend\rating\components\Controller;
use common\commands\site\DenySiteCommand;
use common\commands\site\ApproveSiteCommand;
use frontend\rating\managers\SiteRequestManager;
use common\managers\NotifyManager;

class SitesController extends Controller
{
    public $layout = 'profile';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'edit'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['moderate-edit', 'moderate-list'],
                        'allow' => true,
                        'roles' => ['moderator'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $notify = (new NotifyManager())->getActiveNotify();

        $SiteRequestManager = Yii::$container->get('SiteRequestManager');
        $ScoreRulesManager = Yii::$container->get('ScoreRulesManager');
        $PeriodManager = Yii::$container->get('PeriodManager');

        $sites = $SiteRequestManager->loadUserSiteList();
        $activePeriod = $PeriodManager->getActivePeriod();
        $scoreBySiteTypes = $ScoreRulesManager->scoreBySiteTypes($activePeriod, $sites);

        return $this->render('index', [
            'sites' => $sites,
            'activePeriod' => $activePeriod,
            'scoreBySiteTypes' => $scoreBySiteTypes,
            'notify' => $notify,
        ]);
    }

    public function actionCreate()
    {
        $model = new SiteForm();

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {
            \Yii::$app->commandBus->dispatch(new SaveSiteCommand(\Yii::$app->user->getIdentity(), null, $model));

            return $this->redirect(['sites/sites/index']);
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $site = Site::findOne([
            'id' => $id,
            'user_id' => \Yii::$app->user->getIdentity()->getId(),
        ]);

        if(!$site) {
            throw new NotFoundHttpException();
        }

        $model = new SiteForm();
        $model->setAttributes($site->attributes);

        $model->have_ads = (int)$model->have_ads;

        if($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $command = new SaveSiteCommand(
                \Yii::$app->user->getIdentity(),
                $site,
                $model,
                \Yii::$app->request->getBodyParam('user_comment')
            );

            \Yii::$app->commandBus->dispatch($command);

            return $this->redirect(\Yii::$app->request->getReferrer());
        }

        $siteComments = SiteComment::find()->where('site_id = :site_id', ['site_id' => $site->id])
            ->orderBy('created_datetime ASC')
            ->all();

        return $this->render('form', [
            'site' => $site,
            'model' => $model,
            'siteComments' => $siteComments,
        ]);
    }

    public function actionModerateList()
    {
        $searchModel = new SiteSearch();

        $dataProvider = $searchModel->setSiteStatus(Site::STATUS_ON_MODERATION)->search(\Yii::$app->request->getQueryParams());
        $dataProvider->prepare();

        $siteTypes = SiteType::options();

        return $this->render('moderate_list', [
            'dataProvider' => $dataProvider,
            'siteTypes' => $siteTypes,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionModerateEdit($id)
    {
        $SiteRequestManager = \Yii::$container->get('SiteRequestManager');
        /** @var Site $site */
        $site = Site::find()
            ->where('id = :id', ['id' => $id])
            ->one();

        if(!$site) {
            throw new NotFoundHttpException();
        }

        $error = false;

        $request = \Yii::$app->request;

        $model = new SiteForm();
        $model->setAttributes($site->attributes);

        $model->have_ads = (int)$model->have_ads;

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {

            $command = new SaveSiteByModeratorCommand(
                Yii::$app->user->getIdentity(),
                $site,
                $model
            );

            Yii::$app->commandBus->dispatch($command);

            if($request->getBodyParam('accept')) {
                Yii::$app->commandBus->dispatch(new ApproveSiteCommand($site));
                $SiteRequestManager->updateQueue();
            } elseif($request->getBodyParam('accept_with_comment')) {
                if($request->getBodyParam('accept_comment')) {
                    Yii::$app->commandBus->dispatch(new ApproveSiteCommand($site, $request->getBodyParam('accept_comment'), \Yii::$app->user->identity));
                    $SiteRequestManager->updateQueue();
                } else {
                    $error = 'Не указан комментарий';
                }
            } else {
                if($request->getBodyParam('moderator_comment')) {
                    Yii::$app->commandBus->dispatch(new DenySiteCommand($site,
                        $request->getBodyParam('moderator_comment'),
                        Yii::$app->user->identity
                    ));
                    $SiteRequestManager->updateQueue();
                } else {
                    $error = 'Не указан комментарий';
                }
            }

            if(!$error) {
                return Yii::$app->response->redirect(['sites/sites/moderate-list']);
            }
        }

        $siteTypes = SiteType::options();

        $siteComments = SiteComment::find()->where('site_id = :site_id', ['site_id' => $site->id])
            ->orderBy('created_datetime ASC')
            ->all();

        return $this->render('moderate_edit', compact('site', 'siteTypes', 'error', 'siteComments', 'model'));
    }

}
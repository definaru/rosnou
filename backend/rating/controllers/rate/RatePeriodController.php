<?php

namespace backend\rating\controllers\rate;

use Yii;
use common\models\RateCriteria;
use common\models\RateCriteriaType;
use common\models\SiteType;
use common\models\RatePeriod;
use common\models\RatePeriodSearch;
use common\managers\PeriodManager;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RatePeriodController implements the CRUD actions for RatePeriod model.
 */
class RatePeriodController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'copy' => ['POST'],
                ],
            ],
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

    /**
     * Lists all RatePeriod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RatePeriodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(false);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RatePeriod model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RatePeriod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $PeriodManager = new PeriodManager();


        $model = new RatePeriod();

        $model->request1_accept_flag = 1;
        $model->request2_accept_flag = 1;
        $model->register_accept_flag = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $PeriodManager->setActiveFlag($model);

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RatePeriod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $PeriodManager = new PeriodManager();

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $PeriodManager->setActiveFlag($model);

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Copy an existing RatePeriod model.
     * @param integer $id
     * @return mixed
     */
    public function actionCopy($id)
    {
        $PeriodManager = new PeriodManager();
        
        $model = $this->findModel($id);

        if ($model) {
            $PeriodManager->copyPeriod($model);
        }  else  {
            throw new NotFoundHttpException();
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing RatePeriod model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RatePeriod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RatePeriod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RatePeriod::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCriteria($id)
    {
        $period = $this->findModel($id);

        $siteTypes = SiteType::find()->all();

        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id', ['period_id' => $period->id])->orderBy('title')
            ->all();

        $criteriaTypes = ArrayHelper::index($criteriaTypes, null, 'site_type_id');

        $criterias = RateCriteria::find()
            ->orderBy('title')
            ->all();

        $criterias = ArrayHelper::index($criterias, null, 'type_id');

        return $this->render('criteria', compact(
            'period',
            'siteTypes',
            'criteriaTypes',
            'criterias'
        ));
    }
}

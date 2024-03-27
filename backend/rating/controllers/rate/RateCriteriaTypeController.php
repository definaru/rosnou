<?php

namespace backend\rating\controllers\rate;

use common\models\RatePeriod;
use common\models\SiteType;
use Yii;
use common\models\RateCriteriaType;
use common\models\RateCriteriaTypeSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RateCriteriaTypeController implements the CRUD actions for RateCriteriaType model.
 */
class RateCriteriaTypeController extends Controller
{
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
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RateCriteriaType models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $period = $this->findPeriod($id);

        $searchModel = new RateCriteriaTypeSearch();
        $searchModel->period_id = $period->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'period' => $period,
        ]);
    }

    /**
     * Displays a single RateCriteriaType model.
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
     * Creates a new RateCriteriaType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($periodId, $siteType)
    {
        $period = $this->findPeriod($periodId);
        $siteType = SiteType::find()->where('id = :id', ['id' => $siteType])->one();

        if(!$siteType) {
            throw new NotFoundHttpException("Site type not found");
        }

        $model = new RateCriteriaType();
        $model->period_id = $period->id;
        $model->site_type_id = $siteType->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/rate/rate-period/criteria', 'id' => $period->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'period' => $period,
                'siteType' => $siteType,
            ]);
        }
    }

    /**
     * Updates an existing RateCriteriaType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/rate/rate-period/criteria', 'id' => $model->period_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RateCriteriaType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach($model->rateCriterias as $criteria) {
            $criteria->delete();
        }

        $model->delete();

        return $this->redirect(['/rate/rate-period/criteria', 'id' => $model->period_id]);
    }

    /**
     * Finds the RateCriteriaType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RateCriteriaType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RateCriteriaType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findPeriod($id)
    {
        $period = RatePeriod::find()->byField('id', $id)->one();

        if(!$period) {
            throw new NotFoundHttpException();
        }

        return $period;
    }
}

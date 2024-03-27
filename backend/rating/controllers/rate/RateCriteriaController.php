<?php

namespace backend\rating\controllers\rate;

use common\models\RateCriteriaType;
use Yii;
use common\models\RateCriteria;
use common\models\RateCriteriaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RateCriteriaController implements the CRUD actions for RateCriteria model.
 */
class RateCriteriaController extends Controller
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
     * Lists all RateCriteria models.
     * @return mixed
     */
    public function actionIndex($type)
    {
        $criteriaType = RateCriteriaType::find()
            ->where('id = :id', ['id' => $type])
            ->one();

        if(!$criteriaType) {
            throw new NotFoundHttpException('Criteria type does not exist');
        }

        $searchModel = new RateCriteriaSearch();
        $searchModel->type_id = $criteriaType->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'criteriaType' => $criteriaType,
        ]);
    }

    /**
     * Displays a single RateCriteria model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new RateCriteria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        $criteriaType = RateCriteriaType::find()
            ->where('id = :id', ['id' => $type])
            ->one();

        if(!$criteriaType) {
            throw new NotFoundHttpException('Criteria type does not exist');
        }

        $model = new RateCriteria();
        $model->type_id = $criteriaType->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/rate/rate-period/criteria', 'id' => $criteriaType->period_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'criteriaType' => $criteriaType,
            ]);
        }
    }

    /**
     * Updates an existing RateCriteria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/rate/rate-period/criteria', 'id' => $model->type->period_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RateCriteria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $criteriaType = $model->type;

        $model->delete();

        return $this->redirect(['/rate/rate-period/criteria', 'id' => $criteriaType->period_id]);
    }

    /**
     * Finds the RateCriteria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RateCriteria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RateCriteria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\rating\controllers;

use Yii;
use common\models\Notify;
use common\models\NotifySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\managers\NotifyManager;

/**
 * NotifyController implements the CRUD actions for Notify model.
 */
class NotifyController extends Controller
{
    public function behaviors()
    {
        return [
             'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    ['actions' => ['multydelete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Notify models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotifySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Notify model.
     * @param integer $id
     * @return mixed
     */
     /*
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }*/

    /**
     * Creates a new Notify model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $NotifyManager = new NotifyManager();

        $model = new Notify;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $NotifyManager->setActiveFlag($model);

            $redirect = yii::$app->request->post('goto') == 'list'
                ? ['index']
                : ['update', 'id' => $model->id];

            return $this->redirect($redirect);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Notify model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $NotifyManager = new NotifyManager();

        $model = $this->findModel($id);

        $sendNotify = Yii::$app->request->post('sendnotify');

        if ($sendNotify) {

            $email = Yii::$app->request->post('email');
            $NotifyManager->sendNotify($email, $model);

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $NotifyManager->setActiveFlag($model);

            $redirect = yii::$app->request->post('goto') == 'list'
                ? ['index']
                : ['update', 'id' => $model->id];

            return $this->redirect($redirect);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Notify model.
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
     * Bulk Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMultydelete($param='')
    {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys
        
        // Preventing extra unnecessary query
        if (!$pk) {
          return $this->redirect(['index']);
        }

        foreach($pk as $k => $v) {
          if (is_numeric($v)) $this->findModel($v)->delete();
        }

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Notify model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notify the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notify::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace frontend\rating\controllers\users;

use common\commands\rate\ProcessExaminationByExpert;
use common\commands\rate\ProcessExaminationByExpertCommand;
use common\models\RateCriteriaResultComment;
use common\models\RateRequestSearch;
use common\models\Site;
use domain\ModelSaveException;
use yii\helpers\ArrayHelper;
use common\models\RatePeriod;
use common\models\RateRequest;
use yii\filters\AccessControl;
use common\models\RateCriteria;
use common\models\User;
use yii\web\ForbiddenHttpException;
use yii\base\UserException;
use yii\web\NotFoundHttpException;
use common\models\RateCriteriaType;
use common\models\RateCriteriaResult;
use frontend\rating\components\Exception;
use frontend\rating\components\Controller;
use common\commands\rate\SaveSelfExamination;
use yii\web\Response;
use frontend\rating\managers\PeriodManager;

class ExaminationController extends Controller
{
    public $layout = 'profile';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'results'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['expert-list', 'expert-check'],
                        'allow' => true,
                        'roles' => ['expert'],
                    ],
                    [
                        'actions' => ['add-comment', 'comments-list', 'delete-comment'],
                        'allow' => true,
                        'roles' => ['user', 'expert'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {

        $PeriodManager = \Yii::$container->get(PeriodManager::class);

        /** @var Site $site */
        $site = Site::find()
            ->byUserId(\Yii::$app->user->getId())
            ->byField('id', $id)
            ->one();

        if(!$site) {
            throw new NotFoundHttpException();
        }

        /** @var RatePeriod $activePeriod */
        if(!$activePeriod = $PeriodManager->getActivePeriod()) {
            throw new NotFoundHttpException();
        }

        /** @var RateRequest $rateRequest */
        $rateRequest = RateRequest::find()
            ->byField('site_id', $site->id)
            ->byField('period_id', $activePeriod->id)
            ->one();

        $initialSelfExamination = false; // первичное обследование

        if(!$rateRequest) {
            $rateRequest = RateRequest::createNewRequest($site, $activePeriod);
        }

        if(!$rateRequest->isStatus([RateRequest::STATUS_NOT_FINISHED, RateRequest::STATUS_USER_DRAFT, RateRequest::STATUS_CHECKED])) {
            throw new Exception("You can not edit self-examination");
        }

        if(\Yii::$app->request->isPost ) {

            $data = \Yii::$app->request->getBodyParams();

            if (!$PeriodManager->AcceptRequest1($activePeriod) && $rateRequest->isStatus([RateRequest::STATUS_NOT_FINISHED, RateRequest::STATUS_USER_DRAFT])) {
                throw new Exception("Acceptance of initial applications for self-examination is suspended");    
            }

            if (!$PeriodManager->AcceptRequest2($activePeriod) && $rateRequest->isStatus([RateRequest::STATUS_CHECKED])) {
                throw new Exception("Reception of repeated applications for self-examination is suspended");    
            }

            $do_draft = $data['do_draft'] ?? 0;

            \Yii::$app->commandBus->dispatch(new SaveSelfExamination(
                $rateRequest,
                $data['criteria'] ?? [],
                $do_draft
            ));

            return $this->redirect(['sites/sites/index']);
        }

        $criteriaTypesQuery = RateCriteriaType::find()
            ->where('period_id = :period_id AND site_type_id = :site_type_id', [
                'period_id' => $activePeriod->id,
                'site_type_id' => $site->type_id,
            ]);
            $criteriaTypesQuery->orderBy('title ASC');

            if ($rateRequest->isStatus([RateRequest::STATUS_NOT_FINISHED, RateRequest::STATUS_USER_DRAFT])) {
                $criteriaTypesQuery->andWhere(['hidden_flag' => 0]);
            }
            $criteriaTypes = $criteriaTypesQuery->all();

        $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', ArrayHelper::map($criteriaTypes, 'id', 'id')])
            ->orderBy('id ASC')
            ->all();
        $criterias = ArrayHelper::index($criteriasItems, null, 'type_id');

        $criteriaResults = RateCriteriaResult::find()
            ->where('request_id = :request_id', ['request_id' => $rateRequest->id])
            ->all();

        $criteriaResults = ArrayHelper::index($criteriaResults, 'criteria_id');

        return $this->render('index', compact(
            'site',
            'activePeriod',
            'criteriaTypes',
            'criterias',
            'criteriaResults',
            'PeriodManager'
        ));
    }

    public function actionResults(int $id)
    {
        
        $PeriodManager = \Yii::$container->get(PeriodManager::class);

        /** @var Site $site */
        $site = Site::find()
            ->byUserId(\Yii::$app->user->getId())
            ->byField('id', $id)
            ->one();

        if(!$site) {
            throw new NotFoundHttpException();
        }

        /** @var RatePeriod $activePeriod */
        if(!$activePeriod = $PeriodManager->getActivePeriod()) {
            throw new NotFoundHttpException();
        }

        /** @var RateRequest $rateRequest */
        $rateRequest = RateRequest::find()
            ->byField('site_id', $site->id)
            ->byField('period_id', $activePeriod->id)
            ->one();

        if(!$rateRequest->isStatus(RateRequest::STATUS_CHECKED)) {
            throw new Exception("Examination is not checked yet");
        }

        $criteriaTypes = RateCriteriaType::find()
            ->where('period_id = :period_id AND site_type_id = :site_type_id', [
                'period_id' => $activePeriod->id,
                'site_type_id' => $site->type_id,
            ])
            ->all();

        $criteriasItems = RateCriteria::find()
            ->where(['in', 'type_id', ArrayHelper::map($criteriaTypes, 'id', 'id')])
            ->all();
        $criterias = ArrayHelper::index($criteriasItems, null, 'type_id');

        $criteriaResults = RateCriteriaResult::find()
            ->where('request_id = :request_id', ['request_id' => $rateRequest->id])
            ->all();

        $criteriaResults = ArrayHelper::index($criteriaResults, 'criteria_id');

        return $this->render('results', compact(
            'site',
            'criteriaTypes',
            'criterias',
            'criteriaResults',
            'PeriodManager',
            'activePeriod'
        ));
    }

    public function actionExpertList()
    {
        /** @var RatePeriod $activePeriod */
        $activePeriod = RatePeriod::find()->active()->one();

        $params = \Yii::$app->request->getQueryParams();
        $params['RateRequestSearch']['period_id'] = $activePeriod ? $activePeriod->id : 0;
        $flags = [RateRequest::STATUS_FINISHED, RateRequest::STATUS_EXPERT_DRAFT];

        if(!\Yii::$app->user->can('admin')) {
            $flags[] = RateRequest::STATUS_CHECKED;
        }

        $rateRequests = (new RateRequestSearch)->search($params, $flags);
        $rateRequests->prepare();

        $items = $rateRequests->getModels();

        $expertItems = array_filter($items, function(RateRequest $item) {
            return $item->expert_id == \Yii::$app->user->getId() && !$item->isStatus(RateRequest::STATUS_CHECKED);
        });

        $freeItems = array_filter($items, function($item) {
            return !$item->expert_id;
        });

        $expertFinishedItems = array_filter($items, function(RateRequest $item) {
            return $item->expert_id == \Yii::$app->user->getId() && $item->isStatus(RateRequest::STATUS_CHECKED);
        });

        return $this->render('expert_list', compact(
            'items',
            'freeItems',
            'expertItems',
            'expertFinishedItems'
        ));
    }

    public function actionExpertCheck(int $id)
    {
         $RateRequestManager = \Yii::$container->get('RateRequestManager');
         $CriteriaManager = \Yii::$container->get('CriteriaManager');

        /** @var RatePeriod $activePeriod */
        if(!$activePeriod = RatePeriod::find()->active()->one()) {
            throw new NotFoundHttpException();
        }

        /** @var RateRequest $rateRequest */
        $rateRequest = RateRequest::find()
            ->byField('period_id', $activePeriod->id)
            ->byField('id', $id)
            ->with('site', 'site.type')
            ->one();

        if(!$rateRequest) {
            throw new NotFoundHttpException();
        }

        // Заявка должна быть либо черновиком, либо заершенной 
        if( !$rateRequest->isStatus([RateRequest::STATUS_FINISHED, RateRequest::STATUS_EXPERT_DRAFT])) {
            $ExpertUser = User::findOne($rateRequest->expert_id);
            throw new UserException("RateRequest should be finished or draft. Expert: '". $ExpertUser->email ."'");
        }

        if($rateRequest->expert_id) {
            
            if($rateRequest->expert_id != \Yii::$app->user->getId()) {

                $ExpertUser = User::findOne($rateRequest->expert_id);
                throw new UserException("Examination is for another expert. Expert: '". $ExpertUser->email ."'");

            } else {
                $rateRequest->queue_index = $RateRequestManager->getMaxQueueExpert($rateRequest->expert_id) + 1;
                $rateRequest->save();     
            }
        } else {
            $rateRequest->expert_id = \Yii::$app->user->getId();
            $rateRequest->queue_index = $RateRequestManager->getMaxQueueExpert($rateRequest->expert_id) + 1;
            $rateRequest->save();
            $RateRequestManager->updateQueue();
        }

        // Процесс сохранения данных
        if(\Yii::$app->request->isPost) {
            $data = \Yii::$app->request->getBodyParams();

            //print_r($data); die;

            $do_draft = $data['do_draft'] ?? 0;

            \Yii::$app->commandBus->dispatch(new ProcessExaminationByExpertCommand(
                $rateRequest,
                $data['criteria'] ?? [],
                $do_draft
            ));

            if ( !$do_draft ) {
                $RateRequestManager->updateExpertQueue();
            }

            return $this->redirect(['users/examination/expert-list']);
        }

        $results = $RateRequestManager->loadRateResultList($rateRequest);

        return $this->render('expert_check', [
            'rateRequest' => $rateRequest,
            //'criteriaTypes' => $criteriaTypes,
            //'criterias' => $criterias,
            //'criteriaResults' => $criteriaResults,
        
            'results' => $results,
        ]);
    }

    public function actionAddComment()
    {
        /** @var RatePeriod $activePeriod */
        if(!$activePeriod = RatePeriod::find()->active()->one()) {
            throw new NotFoundHttpException();
        }

        $data = \Yii::$app->request->getBodyParams();

        /** @var RateCriteriaResult $criteriaResult */
        $criteriaResult = RateCriteriaResult::find()
            ->where('id = :id', [
                'id' => $data['result_id'] ?? 0,
            ])
            ->one();

        if(!$criteriaResult) {
            throw new NotFoundHttpException();
        }

        /** @var RateRequest $rateRequest */
        $rateRequest = RateRequest::find()
            ->byField('period_id', $activePeriod->id)
            ->byField('id', $criteriaResult->request_id)
            ->with('site', 'site.type')
            ->one();

        $user = \Yii::$app->user;

        // проверка, что эксперт может добавить комментарий
        if($user->can('expert') AND $rateRequest->expert_id != $user->getId()) {
            throw new ForbiddenHttpException("It's another expert examination");
        }

        // проверка, что пользователь может добавить комментарий
        if($user->can('user') AND $rateRequest->site->user_id != $user->getId()) {
            throw new ForbiddenHttpException("It's not your examination");
        }

        $comment = new RateCriteriaResultComment;
        $comment->body = $data['message'];
        $comment->result_id = $criteriaResult->id;
        $comment->user_id = $user->getId();

        if(!$comment->save()) {
            throw new ModelSaveException($comment);
        }

        $criteriaResult->comment_count += 1;
        $criteriaResult->save();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [$this->commentMap($comment)];
    }

    public function actionCommentsList($result)
    {
        /** @var RatePeriod $activePeriod */
        //if(!$activePeriod = RatePeriod::find()->active()->one()) {
        //    throw new NotFoundHttpException();
        //}

        /** @var RateCriteriaResult $criteriaResult */
        $criteriaResult = RateCriteriaResult::find()
            ->where('id = :id', [
                'id' => $result,
            ])
            ->one();

        if(!$criteriaResult) {
            throw new NotFoundHttpException();
        }

        ///** @var RateRequest $rateRequest */
        //$rateRequest = RateRequest::find()
        //    ->byField('period_id', $activePeriod->id)
        //    ->byField('id', $criteriaResult->request_id)
        //    ->with('site', 'site.type')
        //    ->one();
        //
        //$user = \Yii::$app->user;
        //
        //// проверка, что эксперт может добавить комментарий
        //if($user->can('expert') AND $rateRequest->expert_id != $user->getId()) {
        //    throw new ForbiddenHttpException("It's another expert examination");
        //}
        //
        //// проверка, что пользователь может добавить комментарий
        //if($user->can('user') AND $rateRequest->site->user_id != $user->getId()) {
        //    throw new ForbiddenHttpException("It's not your examination");
        //}

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $comments = RateCriteriaResultComment::find()
            ->where('result_id = ' . $criteriaResult->id)
            ->orderBy('created_datetime')
            ->with(['user'])
            ->all();

        return array_map(function(RateCriteriaResultComment $comment) {
            return $this->commentMap($comment);
        }, $comments);
    }

    public function actionDeleteComment()
    {
        $comment = RateCriteriaResultComment::find()
            ->where('id = :id AND user_id = :user_id', [
                'id' => \Yii::$app->request->getBodyParam('id', 0),
                'user_id' => \Yii::$app->user->getId(),
            ])
            ->one();

        if(!$comment) {
            throw new NotFoundHttpException();
        }

        $comment->delete();

        /** @var RateCriteriaResult $criteriaResult */
        $criteriaResult = RateCriteriaResult::find()
            ->where('id = :id', [
                'id' => $comment->result_id,
            ])
            ->one();

        $criteriaResult->comment_count = $criteriaResult->comment_count > 0 ? $criteriaResult->comment_count - 1 : 0;
        $criteriaResult->save();

        return 'ok';
    }

    /**
     * @param RateCriteriaResultComment $comment
     * @return array
     */
    private function commentMap(RateCriteriaResultComment $comment)
    {
        return [
            'id' => $comment->id,
            'author' => $comment->user->firstname,
            'avatar' => $comment->user->getAvatar($this->view),
            'publish_time' => substr($comment->created_datetime, 0, strpos($comment->created_datetime, '.')),
            'content' => $comment->body,
            'answer' => $comment->user->id != \Yii::$app->user->getId(),
        ];
    }
}
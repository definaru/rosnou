<?php

namespace frontend\rating\controllers\users;

use Yii;
use common\user\Name;
use common\models\Site;
use common\models\User;
use yii\filters\AccessControl;
use common\managers\UploadManager;
use frontend\rating\forms\ProfileForm;
use common\commands\SaveProfileCommand;
use frontend\rating\components\Controller;

class ProfileController extends Controller
{
    public $layout = 'profile';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new ProfileForm();

        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        $model->setAttributes([
            'currentEmail' => $user->email,
            'email' => $user->email,
            'last_name' => $user->lastname,
            'first_name' => $user->firstname,
            'middle_name' => $user->middlename,
            'org_name' => $user->orgname,
            'org_position' => $user->position,
        ]);

        $uploadManager = Yii::$container->get(UploadManager::class);
        $request = Yii::$app->request;
        
        // Сохраняем обрезанную аватарку
        if ( $request->post('ch-cropper-crop-ajax') ) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $avaFile = $user->id . '-ava.jpg';

            $filetmp = $uploadManager->getPath('images/avatars', $user->id . '-tmp.jpg');
            $fileava = $uploadManager->getPath('images/avatars', $avaFile);
            
            $uploadManager->cropAvatar(
                $filetmp['file'], $fileava['file'],
                [$request->post('x'), $request->post('y')],
                [$request->post('width'), $request->post('height')],
                [400, 400]
            );
            
            $user->setAvatar($avaFile);

            if ($user->update(false)) {
                return [ 'path' => $fileava['web'], 'output' => '', 'message'=>''];
            }

            return ['output'=>'', 'message'=>''];
        }
        
        // Удаляем автарку юзера
        if ( $request->post('ch-cropper-delete-ajax') ) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $user->setAvatar(null)->save();

            return ['output' => $user->getAvatar(), 'message' => ''];
        }
        
        // загружаем tmp файл аватарки
        if ( $request->post('ch-cropper-tmp-ajax') ) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $file = $uploadManager->getInput('ch-cropper-file');

            $uploadManager->sizeException($file, 5);
            $uploadManager->typeException($file, 'image');

            $result = $uploadManager->saveFile($file, 'images/avatars', $user->id . '-tmp.jpg');

            // read your posted model attributes
            return ['path' => $result['web'], 'output' => '', 'message' => ''];
        }

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {
            $command = new SaveProfileCommand(
                $user,
                new Name(
                    $model->first_name,
                    $model->last_name,
                    $model->middle_name
                ),
                $model->email,
                $model->password,
                $model->org_name,
                $model->org_position
            );

            \Yii::$app->commandBus->dispatch($command);

            $this->redirect(\Yii::$app->request->getReferrer());
        }

        return $this->render('index', [
            'model' => $model,
            'user' => $user,
        ]);
    }
}
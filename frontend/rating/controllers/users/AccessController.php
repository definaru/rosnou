<?php

namespace frontend\rating\controllers\users;

use common\commands\user\ResendEmailVerificationCommand;
use domain\token\services\VerificationTokenService;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\user\Name;
use common\models\User;
use common\models\VerificationToken;
use frontend\rating\forms\LoginForm;
use common\commands\user\LoginUserCommand;
use common\commands\user\LogoutUserCommand;
use frontend\rating\forms\RegisterForm;
use frontend\rating\components\Exception;
use frontend\rating\components\Controller;
use common\commands\RecoverPasswordCommand;
use common\commands\user\VerifyEmailTokenCommand;
use common\commands\user\RegisterUserCommand;
use frontend\rating\forms\PasswordRecoveryForm;
use frontend\rating\forms\PasswordRecoverEnterForm;
use common\commands\SendRecoverPasswordMessageCommand;
use frontend\rating\managers\PeriodManager;

class AccessController extends Controller
{
    public $layout = 'page';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function($rule, $action) {
                    return \Yii::$app->response->redirect(['users/profile']);
                },
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout', 'verify-email-token'],
                        'roles' => ['@'],
                        'mustBeActivated' => false,
                    ],
                    [
                        'allow' => true,
                        'actions' => ['not-activated', 'verification-email-resent'],
                        'roles' => ['@'],
                        'mustBeActivated' => false,
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'login',
                            'registration',
                            'registration-done',
                            'password-recover',
                            'password-recover-message-sent',
                            'password-recover-token',
                            'verify-email-token',
                        ],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionRegistration()
    {
        $model = new RegisterForm();

        $PeriodManager = \Yii::$container->get(PeriodManager::class);

        $acceptRegistration = $PeriodManager->acceptRegistration();

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {

            if( !Yii::$app->request->post('inorobo') ){
                throw new \Exception('Spambot detected');
            }

            if (!$acceptRegistration) {
                 throw new Exception("Registration of members is suspended");
            }

            Yii::$app->commandBus->dispatch(new RegisterUserCommand(
                new Name(
                    $model->first_name,
                    $model->last_name,
                    $model->middle_name
                ),
                $model->email,
                $model->password,
                $model->org_name,
                $model->org_position
            ));

            $this->redirect(Url::toRoute('/users/registrate_done'));
        }

        return $this->render('registration', [
            'model' => $model,
            'acceptRegistration' => $acceptRegistration,
        ]);
    }

    public function actionRegistrationDone()
    {
        return $this->render('registration_done');
    }

    public function actionLogin()
    {
        $model = new LoginForm();

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {
            $user = User::findOne(['email' => $model->email]);

            Yii::$app->commandBus->dispatch(new LoginUserCommand($user));

            $this->redirect(Url::toRoute('users/profile/index'));
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->commandBus->dispatch(new LogoutUserCommand(Yii::$app->user->getIdentity()));
        }

        $this->redirect(Url::home());
    }

    public function actionPasswordRecover()
    {
        $model = new PasswordRecoveryForm();

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {
            /** @var User $user */
            $user = User::findOne(['email' => $model->email]);

            Yii::$app->commandBus->dispatch(new SendRecoverPasswordMessageCommand($user));

            $this->redirect(Url::toRoute('users/access/password-recover-message-sent'));
        }

        return $this->render('password_recover', [
            'model' => $model,
        ]);
    }

    public function actionPasswordRecoverMessageSent()
    {
        return $this->render('password_recover_message_sent');
    }

    public function actionPasswordRecoverToken(string $token)
    {
        /** @var VerificationTokenService $tokenManager */
        $tokenManager = Yii::$container->get(VerificationTokenService::class);

        $token = $tokenManager->get($token, VerificationToken::TYPE_PASSWORD_RECOVERY);

        $model = new PasswordRecoverEnterForm();

        if($model->load(\Yii::$app->request->post()) AND $model->validate()) {
            $user = User::findOne(['id' => $token->user_id]);

            if(!$user) {
                throw new Exception("User does not exist");
            }

            Yii::$app->commandBus->dispatch(new RecoverPasswordCommand($token->token, $user, $model->password));

            $this->redirect(Url::toRoute('users/access/login'));
        }

        return $this->render('password_recover_token', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmailToken(string $token)
    {
        Yii::$app->commandBus->dispatch(new VerifyEmailTokenCommand($token));

        $this->redirect(Url::toRoute('users/access/login'));
    }

    public function actionNotActivated()
    {
        return $this->render('not_activated');
    }

    public function actionVerificationEmailResent()
    {
        $model = Yii::$app->user->getIdentity();

        try {
            Yii::$app->commandBus->dispatch(new ResendEmailVerificationCommand($model));
        } catch (\Exception $e) {

        }

        return $this->render('verification_email_resent', [
            'email' => $model->email,
        ]);
    }
}
<?php

namespace common\commands\handlers\user;

use yii\helpers\Url;
use common\models\User;
use common\models\VerificationToken;
use common\emails\RegistrationMessage;
use common\components\EmailNotification;
use domain\user\services\RegisterUserService;
use common\commands\user\RegisterUserCommand;
use domain\token\services\VerificationTokenService;

class RegisterUserHandler
{
    /**
     * @var RegisterUserService
     */
    private $registerUserService;

    /**
     * @var VerificationTokenService
     */
    private $tokenService;

    /**
     * @var EmailNotification
     */
    private $emailNotification;

    public function __construct(
        RegisterUserService $registerUserService,
        EmailNotification $emailNotification,
        VerificationTokenService $tokenService
    ) {
        $this->registerUserService = $registerUserService;
        $this->emailNotification = $emailNotification;
        $this->tokenService = $tokenService;
    }

    /**
     * @param RegisterUserCommand $command
     * @return User
     */
    public function handle(RegisterUserCommand $command)
    {
        $user = $this->registerUserService->registerUser(
            $command->name(),
            $command->email(),
            $command->password(),
            $command->orgName(),
            $command->orgPosition()
        );

        $token = $this->tokenService->create($user, VerificationToken::TYPE_EMAIL_VERIFICATION);

        $this->emailNotification->send(new RegistrationMessage($user, $command->password(), $this->emailVerificationUrl($token)));

        return $user;
    }

    /**
     * @param VerificationToken $token
     * @return string
     */
    private function emailVerificationUrl(VerificationToken $token)
    {
        return Url::base(true) . Url::toRoute(['users/access/verify-email-token', 'token' => $token->token]);
    }
}
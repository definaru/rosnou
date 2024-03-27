<?php

namespace common\commands\handlers\user;

use yii\helpers\Url;
use common\models\VerificationToken;
use common\components\EmailNotification;
use common\emails\VerificationEmailMessage;
use domain\token\services\VerificationTokenService;
use common\commands\user\ResendEmailVerificationCommand;

class ResendEmailVerificationHandler
{
    /**
     * @var VerificationTokenService
     */
    private $tokenService;

    /**
     * @var EmailNotification
     */
    private $emailNotification;

    public function __construct(VerificationTokenService $tokenService, EmailNotification $emailNotification)
    {
        $this->tokenService = $tokenService;
        $this->emailNotification = $emailNotification;
    }

    public function handle(ResendEmailVerificationCommand $command)
    {
        $token = $this->tokenService->create($command->user(), VerificationToken::TYPE_EMAIL_VERIFICATION);

        $this->emailNotification->send(new VerificationEmailMessage($command->user(), $this->emailVerificationUrl($token)));
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
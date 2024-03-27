<?php

namespace common\commands\handlers;

use common\components\EmailNotification;
use common\emails\RecoverPasswordMessage;
use common\models\VerificationToken;
use domain\token\services\VerificationTokenService;
use common\commands\SendRecoverPasswordMessageCommand;

class SendRecoverPasswordMessageHandler
{

    /**
     * @var EmailNotification
     */
    private $emailNotification;

    /**
     * @var VerificationTokenService
     */
    private $tokenService;

    public function __construct(EmailNotification $emailNotification, VerificationTokenService $tokenService)
    {
        $this->emailNotification = $emailNotification;
        $this->tokenService = $tokenService;
    }

    /**
     * @param SendRecoverPasswordMessageCommand $command
     * @return $this
     */
    public function handle(SendRecoverPasswordMessageCommand $command)
    {
        $token = $this->tokenService->create($command->user(), VerificationToken::TYPE_PASSWORD_RECOVERY);

        $this->emailNotification->send(new RecoverPasswordMessage($command->user(), $token->token));

        return $this;
    }
}
<?php

namespace common\emails;

use common\models\User;
use domain\notification\EmailMessage;
use common\models\User as UserModel;
use yii\helpers\Url;

class VerificationEmailMessage implements EmailMessage
{
    /**
     * @var UserModel
     */
    private $user;

    /**
     * @var string
     */
    private $emailVerificationUrl;

    public function __construct(User $user, string $emailVerificationUrl)
    {
        $this->user = $user;
        $this->emailVerificationUrl = $emailVerificationUrl;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'user/verification_email';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'user' => $this->user,
            'emailVerificationUrl' => $this->emailVerificationUrl,
        ];
    }

    /**
     * @return string
     */
    public function from(): string
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function to()
    {
        return $this->user->email;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return 'Подтверждение почты';
    }
}
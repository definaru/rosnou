<?php

namespace common\emails;

use domain\notification\EmailMessage;
use common\models\User as UserModel;

class RegistrationMessage implements EmailMessage
{
    /**
     * @var UserModel
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $emailVerificationUrl;

    public function __construct(UserModel $user, string $password, string $emailVerificationUrl)
    {
        $this->user = $user;
        $this->password = $password;
        $this->emailVerificationUrl = $emailVerificationUrl;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'user/registration';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'user' => $this->user,
            'password' => $this->password,
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
        return 'Активация аккаунта в системе Общероссийского рейтинга образовательных сайтов';
    }
}
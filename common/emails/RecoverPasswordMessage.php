<?php

namespace common\emails;

use domain\notification\EmailMessage;
use common\models\User as UserModel;
use yii\helpers\Url;

class RecoverPasswordMessage implements EmailMessage
{
    /**
     * @var UserModel
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    public function __construct(UserModel $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'user/password_recover_sent';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        $passwordRecoverUrl = Url::base(true) . Url::toRoute(['users/access/password-recover-token', 'token' => $this->token]);

        return [
            'user' => $this->user,
            'passwordRecoverUrl' => $passwordRecoverUrl,
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
        return 'Восстановление пароля в системе Общероссийского рейтинга образовательных сайтов';
    }
}
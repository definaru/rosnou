<?php

namespace frontend\rating\forms;

use Yii;
use domain\user\PasswordHasher;

use common\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $password;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class],

            ['password', 'required'],
            ['password', 'checkPassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Эл. почта',
            'password' => 'Пароль',
        ];
    }

    public function checkPassword($attribute)
    {
        $hasher = Yii::$container->get(PasswordHasher::class);

        if(!$user = User::findOne(['email' => $this->email])) {
            return;
        }

        if(!$hasher->verify($this->$attribute, $user->password_hash)) {
            $this->addError('password', '');
        }
    }
}
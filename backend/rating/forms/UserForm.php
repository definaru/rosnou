<?php

namespace backend\rating\forms;

use common\models\User;
use domain\user\PasswordHasher;

class UserForm extends User
{
    public $password;

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge([
            ['password', 'hashPassword'],
        ], parent::rules());
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['password']);
    }

    /**
     * @return bool
     */
    public function hashPassword()
    {
        $this->password_hash = \Yii::$container->get(PasswordHasher::class)->hash($this->password);

        return true;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => 'Пароль',
        ]);
    }
}
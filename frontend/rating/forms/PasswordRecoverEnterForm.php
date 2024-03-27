<?php

namespace frontend\rating\forms;

use common\models\User;
use yii\base\Model;

class PasswordRecoverEnterForm extends Model
{
    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $password_confirm;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'length' => [3, 20]],

            ['password_confirm', 'required'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'password_confirm' => 'Повторите пароль',
        ];
    }
}
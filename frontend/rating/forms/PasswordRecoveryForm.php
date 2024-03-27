<?php

namespace frontend\rating\forms;

use common\models\User;
use yii\base\Model;

class PasswordRecoveryForm extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Эл. почта',
        ];
    }
}
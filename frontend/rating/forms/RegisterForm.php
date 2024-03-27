<?php

namespace frontend\rating\forms;

use yii\base\Model;
use common\models\User;

class RegisterForm extends Model
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
     * @var
     */
    public $password_confirm;

    /**
     * @var
     */
    public $last_name;

    /**
     * @var
     */
    public $first_name;

    /**
     * @var
     */
    public $middle_name;

    /**
     * @var
     */
    public $org_name;

    /**
     * @var
     */
    public $org_position;

    /**
     * @var
     */
    public $captcha;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class],

            ['password', 'required'],
            ['password', 'string', 'length' => [3, 20]],

            ['password_confirm', 'required'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],

            ['last_name', 'required'],
            ['last_name', 'string', 'length' => [1, 255]],

            ['first_name', 'required'],
            ['last_name', 'string', 'length' => [1, 255]],

            ['middle_name', 'required'],
            ['last_name', 'string', 'length' => [1, 255]],

            ['org_name', 'required'],

            ['org_position', 'required'],

            ['captcha', 'required'],
            ['captcha', 'captcha', 'captchaAction' => 'home/captcha'],

            [['last_name', 'first_name', 'middle_name'], 'string', 'length' => [1, 255]],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_confirm' => 'Повторите пароль',
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'org_name' => 'Название организации',
            'org_position' => 'Должность',
            'captcha' => 'Введите текст с картинки',
        ];
    }
}
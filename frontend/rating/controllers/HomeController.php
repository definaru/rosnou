<?php

namespace frontend\rating\controllers;

use yii\captcha\CaptchaAction;
use yii\web\Controller;

class HomeController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'test' : null,
            ],
        ];
    }
}
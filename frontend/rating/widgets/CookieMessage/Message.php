<?php
namespace frontend\rating\widgets\CookieMessage;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Message extends Widget {

    public function init() {

    }

    public function run(){

        $applyMessage = $_COOKIE['cookie_message_apply'] ?? 0;

        if( !$applyMessage ){
            return $this->render('message');
        }

        return false;
    }
}

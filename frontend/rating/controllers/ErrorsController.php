<?php

namespace frontend\rating\controllers;

use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

class ErrorsController extends \yii\web\Controller
{
    public $layout = false;

    /**
     * @var string the view file to be rendered. If not set, it will take the value of [[id]].
     * That means, if you name the action as "error" in "SiteController", then the view name
     * would be "error", and the corresponding view file would be "views/site/error.php".
     */
    public $view;

    /**
     * @var string the name of the error when the exception name cannot be determined.
     * Defaults to "Error".
     */
    public $defaultName;

    /**
     * @var string the message to be displayed when the exception message contains sensitive information.
     * Defaults to "An internal server error occurred.".
     */
    public $defaultMessage;

    public function actionHandle()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, Yii::t('yii', 'Page not found.'));
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = $this->defaultName ?: Yii::t('yii', 'Error');
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = $this->defaultMessage ?: Yii::t('yii', 'An internal server error occurred.');
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render($this->view($code), [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
                'handler' => Yii::$app->getErrorHandler(),
            ]);
        }
    }

    /**
     * @param $code
     * @return string
     */
    protected function view($code)
    {
        switch ($code) {
            case 404:
                return '404';

            default:
                return YII_DEBUG ? '@yii/views/errorHandler/exception.php' : 'general';
        }
    }
}

<?php

namespace domain;

use Throwable;
use yii\base\Model;

class ModelSaveException extends Exception
{
    public function __construct(Model $model, $code = 0, Throwable $previous = null)
    {
        $errors = [];

        foreach($model->getErrors() as $attribute => $errors) {
            $errors [] = $attribute . ": " . implode('; ', $errors);
        }

        $this->message = "Errors occurred while saving model " . get_class($model) . ": " . implode('. ', $errors);
    }
}
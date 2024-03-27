<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RatePeriod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rate-period-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'slug')->textInput() ?>

    <?= $form->field($model, 'active_flag')->checkbox() ?>
    <?= $form->field($model, 'request1_accept_flag')->checkbox(['label' => 'принимать заявки на первичное самообследование']) ?>
    <?= $form->field($model, 'request2_accept_flag')->checkbox(['label' => 'принимать заявки на вторичное самообследование']) ?>
    <?= $form->field($model, 'register_accept_flag')->checkbox(['label' => 'прием заявок на участие в рейтинге']) ?>
    <?= $form->field($model, 'finished_flag')->checkbox() ?>
    <?= $form->field($model, 'list_order')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

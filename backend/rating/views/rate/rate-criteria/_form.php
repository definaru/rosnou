<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RateCriteria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rate-criteria-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'score')->textInput() ?>


	<?= $form->field($model, 'field_type')->widget(kartik\widgets\Select2::classname(), [
	    'data' => (new common\models\RateCriteria)->getCriteriaTypeList(),
	    'options' => ['placeholder' => 'Выберите тип критерия ...',
	    ],
	]) ?>
	<?= $form->field($model, 'field_name')->widget(kartik\widgets\Select2::classname(), [
	    'data' => (new common\models\RateCriteria)->getCriteriaFieldList(),
	    'options' => ['placeholder' => 'Выберите название поля ...',
	    ],
	    'pluginOptions' => [
        	'allowClear' => true
    	],	    
	]) ?>
	<?= $form->field($model, 'function')->widget(kartik\widgets\Select2::classname(), [
	    'data' => (new common\models\RateCriteria)->getCriteriaFunctionList(),
	    'options' => ['placeholder' => 'Выберите название функции ...',
	    ],
	    'pluginOptions' => [
        	'allowClear' => true
    	],
	]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\builder\TabularForm;
use common\widgets\eip\InputFile;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
          'type'=>ActiveForm::TYPE_HORIZONTAL,
          'options'=>['enctype'=>'multipart/form-data']
    ]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

'password'=>['type'=> Form::INPUT_PASSWORD, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']],

'lastname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

'firstname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

'middlename'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

'orgname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

'position'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']],

'is_moderator'=>['type'=> Form::INPUT_CHECKBOX],
'is_expert'=>['type'=> Form::INPUT_CHECKBOX],
'is_admin'=>['type'=> Form::INPUT_CHECKBOX],

    ]
    ]);

    echo Html::button(
        Yii::t('app', 'Cancel'),
        [
            'class' => 'btn btn-default',
            'style' => 'margin-right: 20px',
            'onclick' => 'window.location = "' . Url::to(['index']) . '"'
        ]
    );

    echo Html::submitButton(
        $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
        [
            'class' => 'btn btn-primary',
            'style' => 'margin-right: 10px',
            'name' => 'goto',
            'value' => 'list'
        ]
    );

    echo Html::submitButton(
        Yii::t('app', 'Apply'),
        [
            'class' => 'btn btn-primary',
            'style' => 'margin-right: 0px',
        ]
    );

    ActiveForm::end(); ?>

</div>

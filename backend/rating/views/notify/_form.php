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
 * @var common\models\Notify $model
 * @var yii\widgets\ActiveForm $form
 */

$attributes_array = [

'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Заголовок...']], 

'body'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass'=>\vova07\imperavi\Widget::className(),
                'options' => [
                    'settings'=>[
                        'toolbarFixed'=>false,
                        'minHeight' => 100,
                    ]
                ],
                //'type'=> Form::INPUT_TEXTAREA
                //'options'=>['placeholder'=>yii::t('app', 'Enter').' Текст...','rows'=> 6]
            ], 

'finish_datetime'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'active_flag'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>yii::t('app', 'Enter').' Активность...']], 

'sendemail_flag'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>yii::t('app', 'Enter').' Разослать на email...']], 
];

if (!$model->isNewRecord) {
    $attributes_array['sendfinish_flag'] = ['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>yii::t('app', 'Enter').' Cообщение разослано...', 'disabled' => true]];
}    
?>

<div class="notify-form">

    <?php $form = ActiveForm::begin([
          'type'=>ActiveForm::TYPE_HORIZONTAL,
          'options'=>['enctype'=>'multipart/form-data']
    ]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => $attributes_array,
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
    if (!$model->isNewRecord) {
        echo '</br></br>';
        echo 'Email ';
        echo Html::input('input','email') ;
        echo '</br></br>';
        echo Html::submitButton('Отправить тестовое уведомление', ['class' => 'btn btn-primary','name'=>'sendnotify','value' => '1']);
    }

    ActiveForm::end(); ?>
</div>

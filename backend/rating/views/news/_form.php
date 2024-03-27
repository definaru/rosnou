<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\News $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
          'type'=>ActiveForm::TYPE_HORIZONTAL,
          'options'=>['enctype'=>'multipart/form-data']
    ]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Title...']],

'slug'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Slug...']], 

//'h1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' H1...']],

'preview'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass'=> \backend\rating\widgets\Editor::className(),
                'options' => ['options' => ['rows' => 6]],
            ], 

'content'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass'=>\backend\rating\widgets\Editor::className(),
                //'type'=> Form::INPUT_TEXTAREA
                //'options'=>['placeholder'=>yii::t('app', 'Enter').' Content...','rows'=> 6]
            ],

'is_published'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>yii::t('app', 'Enter').' Is Published...']],

'publish_date'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]],

'meta_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Title...']], 

'meta_keywords'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Keywords...']], 

'meta_description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Description...']], 





//'views_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Views Count...']],

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

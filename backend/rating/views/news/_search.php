<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;

use kartik\builder\TabularForm;

/**
 * @var yii\web\View $this
 * @var common\models\News $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-search">

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'action' => ['index'],
        'method' => 'get',
    ]);
    echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 2,

    'attributes' => [

'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Title...']], 

//'slug'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Slug...']], 

//'h1'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' H1...']], 

 

 

//'meta_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Title...']], 

//'meta_keywords'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Keywords...']], 

//'meta_description'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Meta Description...']], 

//'publish_date'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATE]], 

//'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Created At...']], 

//'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Updated At...']], 

'is_published'=>['type'=> Form::INPUT_CHECKBOX, 'options'=>['placeholder'=>yii::t('app', 'Enter').' Is Published...']], 

//'views_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Views Count...']], 

    ]
    ]);

    ?>
    <div class="form-group">
        <?= Html::a(Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default', 'style' => 'margin-left: 15px; margin-right: 10px']) ?>
        <?= Html::submitButton(Yii::t('app','Search'), ['class' => 'btn btn-default', 'onclick' => ' $.pjax.reload({container: "#w2", data: $("#w0").serialize()  }); return false;']) ?>
  
    </div>

    <?php ActiveForm::end(); ?>

</div>

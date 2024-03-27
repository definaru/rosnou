<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use common\models\User;

use kartik\builder\TabularForm;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-search">

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

//'login'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']],

'email'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']],
'role'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => kartik\widgets\Select2::className(),
                'options' => [
                    'data' => User::getRoles(),
                    'options' => ['placeholder' => 'Выбрать...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ], 
//'password_hash'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

//'lastname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

//'firstname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

//'middlename'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

//'orgname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

//'position'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ...']], 

    ]
    ]);

    ?>
    <div class="form-group">
        <?= Html::a(Yii::t('app', 'Reset'), ['index'], ['class' => 'btn btn-default', 'style' => 'margin-left: 15px; margin-right: 10px']) ?>
        <?= Html::submitButton(Yii::t('app','Search'), ['class' => 'btn btn-default', 'onclick' => ' $.pjax.reload({container: "#w2", data: $("#w0").serialize()  }); return false;']) ?>
  
    </div>

    <?php ActiveForm::end(); ?>

</div>

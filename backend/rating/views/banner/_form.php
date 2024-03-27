<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\builder\TabularForm;
use frontend\rating\widgets\eip\InputFile;

/**
 * @var yii\web\View $this
 * @var common\models\Banner $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin([
          'type'=>ActiveForm::TYPE_HORIZONTAL,
          'options'=>['enctype'=>'multipart/form-data']
    ]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Title...']], 
'url'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ссылку...']], 

'image_file'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => InputFile::className(),
                'options' => [
                    'language'      => 'ru',
                    'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
                    //'filter'        => ['text', 'application'],    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                    'options'       => ['class' => 'form-control'],
                    'buttonOptions' => ['class' => 'btn btn-default'],
                    'multiple'      => false,       // возможность выбора нескольких файлов
                    'path'          => 'canners',
                    'show_preview'   => false
                ],
            ], 

'type_index'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => kartik\widgets\Select2::className(),
                'options' => [
                    'data' => (new common\models\Banner)->getTypeIndexList(),
                    'options' => ['placeholder' => 'Выбрать...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ], 

'list_order'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' порядок сотировки...']], 

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

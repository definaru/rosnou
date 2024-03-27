<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;

use kartik\builder\TabularForm;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var common\models\Site $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="site-search">

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

        'id'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' ID...']],

        'type_id'=>[
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => kartik\widgets\Select2::className(),
                'options' => [
                    'data' => ArrayHelper::map( common\models\SiteType::find()->all(), 'id', 'title'),
                    'options' => ['placeholder' => 'Выбрать...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ],
            ], 

        'status_index'=>[
                        'type'=> Form::INPUT_WIDGET,
                        'widgetClass' => kartik\widgets\Select2::className(),
                        'options' => [
                            'data' => (new common\models\Site)->getStatusIndexList(),
                            'options' => ['placeholder' => 'Выбрать...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ],
                    ], 

            'user_id' => [
                'type'=> Form::INPUT_WIDGET,
                'widgetClass' => kartik\widgets\Select2::className(),


                'options' => [

                    // данные для селектора (массив id => text)
                    //'data' => $curatorListSelect,

                    'options' => [
                        'placeholder' => 'Выбрать...',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [

                        'allowClear' => false,
                        'minimumInputLength' => 3,

                        // Настраиваем получение списка элементов
                        'ajax' => [
                            'url' => 'userslist',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],

                        // отключаем экранирование html
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),

                        // шаблон строки
/*                        'templateSelection' => new JsExpression('function(site) {
                            return site.text.substr(0,100) +
                                   "<a style=\"float:right\" href=\"../sites/update?site_id=" + site.id +
                                   "&user_id='. $model->id .'\">задать права</a>"; }'),*/

                        'templateResult' => new JsExpression('function (user) { return user.text; }'),
                    ],
                ],
            ],

        'district_id'=>[
                        'type'=> Form::INPUT_WIDGET,
                        'widgetClass' => kartik\widgets\Select2::className(),
                        'options' => [
                            'data' => ArrayHelper::map( common\models\SiteDistrict::find()->all(), 'id', 'title'),
                            'options' => ['placeholder' => 'Выбрать...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ],
                    ], 

        'subject_id'=>[
                        'type'=> Form::INPUT_WIDGET,
                        'widgetClass' => kartik\widgets\Select2::className(),
                        'options' => [
                            'data' => ArrayHelper::map( common\models\SiteSubject::find()->all(), 'id', 'title'),
                            'options' => ['placeholder' => 'Выбрать...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ],
                    ], 

        'domain'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Domain...']], 

        'title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Title...']], 

        //'org_title'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Org Title...']], 

        //'location'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Location...']], 

        //'headname'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Headname...']], 

        //'created_timestamp'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=> yii::t('app', 'Enter').' Created Timestamp...']], 

    ]
    ]);

    ?>
    <div class="form-group">
        <?= Html::a(Yii::t('app', 'Сбросить'), ['index'], ['class' => 'btn btn-default', 'style' => 'margin-left: 15px; margin-right: 10px']) ?>
        <?= Html::submitButton(Yii::t('app','Поиск'), ['class' => 'btn btn-default', 'onclick' => ' $.pjax.reload({container: "#w2", data: $("#w0").serialize()  }); return false;']) ?>
  
    </div>

    <?php ActiveForm::end(); ?>

</div>

<p></p>
<?php
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

?>
<div class="header-addon">
    <?php $form = ActiveForm::begin([
    'id'      => 'filter',
    'action' => ['uchastniki/list'],
    'method' => 'get',
    'options' => [
        'class' => "form-inline",
        'data-pjax' => true,
    ],
  ])?>

    <div class="form-group" id="site_category">
        <label class="h3 small">
            Категория сайта
        </label>
        <?=$form->field($model, 'site_type_id')->label(false)->widget(Select2::classname(), [
            'data' => $siteTypes_list,
            'options' => ['placeholder' => 'Выберите тип сайта...'],
            'pluginOptions' => [
              'allowClear' => true
            ],
            'pluginEvents' => [
                  'change' => 'function(){ $("#filter").submit() }'
            ],
            ]);
          ?>
    </div>
    <div class="form-group" id="site_district">
        <label class="h3 small">
            Федеральный округ
        </label>
        <?=$form->field($model, 'district_id')->label(false)->widget(Select2::classname(), [
            'data' => $siteDistrict_list,
            'options' => ['placeholder' => 'Выберите федеральный округ...'],
            'pluginOptions' => [
              'allowClear' => true
            ],
            'pluginEvents' => [
                  'change' => 'function(){ $("#filter").submit() }'
            ],
            ]);?>
    </div>
    <div class="form-group" id="site_subject">
        <label class="h3 small">
            Субъект Федерации
        </label>
        <?=$form->field($model, 'subject_id')->label(false)->widget(Select2::classname(), [
            'data' => $siteSubject_list,
            'options' => ['placeholder' => 'Выберите субъект федерации...'],
            'pluginOptions' => [
              'allowClear' => true
            ],
            'pluginEvents' => [
                  'change' => 'function(){ $("#filter").submit() }'
            ],
            ]);?>
    </div>
        <button class="btn btn-green" type="submit">
            Обновить
        </button>
    <?php ActiveForm::end()?>
</div>
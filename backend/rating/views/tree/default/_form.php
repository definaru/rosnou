<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\builder\Form;

foreach ($errors as $messages) {
    foreach ($messages as $message) {
        ?>
        <div class="alert alert-danger" role="alert"><? print($message) ?></div>
    <? }
} ?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput() ?>

<?= $form->field($model, 'parent_id')->widget(kartik\widgets\Select2::className(), [
    'data' => $sections,
    'hideSearch' => true,
    'options' => [
        'placeholder' => 'Выбрать...',
        'options' => [
            $model->id => ['disabled' => true],
        ],

    ],
    'pluginOptions' => [
        'allowClear' => true,
    ],
]) ?>

<?= $form->field($model, 'route')->textInput() ?>

<?= $form->field($model, 'module')->widget(kartik\widgets\Select2::className(), [
    'data' => $modules,
    'hideSearch' => true,
    'options' => ['placeholder' => 'Выбрать...'],
    'pluginOptions' => [
        'allowClear' => true,
    ],
]) ?>

<?php //$form->field($model, 'menu_title')->textInput() ?>
<?= $form->field($model, 'body')->widget(\backend\rating\widgets\Editor::className())?>


<?php //$form->field($model, 'menu_order')->textInput() ?>
<?php //$form->field($model, 'template')->textInput() ?>
<?php //$form->field($model, 'menu_flag')->checkbox() ?>

<input type="submit" name="save" class="btn btn-default" value="Сохранить">

<?php ActiveForm::end(); ?>

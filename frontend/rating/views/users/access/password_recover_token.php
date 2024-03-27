<?php use yii\widgets\ActiveForm;

$this->breadcrumbs[] = 'Восстановление пароля'?>
<?php $this->title = 'Восстановление пароля'?>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <p>
            Введите новый пароль
        </p>
        <?php $form = ActiveForm::begin([
            'id' => 'forget',
            //'action' => 'users/registrate_do',
            'options' => [
                //'class' => 'form-inline',
            ],
            'enableClientScript' => false,
            'fieldConfig' => [
                'template' => "{label}\n{input}{error}",
                'labelOptions' => [
                    'class' => 'sr-only',
                ],
            ],
        ])?>
            <?=$form->field($model, 'password', ['inputOptions' => [
                'placeholder' => $model->getAttributeLabel('password'),
                'class' => 'form-control',
            ]])->passwordInput()?>

            <?=$form->field($model, 'password_confirm', ['inputOptions' => [
                'placeholder' => $model->getAttributeLabel('password_confirm'),
                'class' => 'form-control',
            ]])->passwordInput()?>

            <button type="submit" class="btn btn-blue" style="vertical-align: top;">Восстановить</button>
        <?php ActiveForm::end()?>
    </div>
</div>
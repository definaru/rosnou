<?php use yii\widgets\ActiveForm;

$this->breadcrumbs[] = 'Восстановление пароля'?>
<?php $this->title = 'Восстановление пароля'?>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <p>
            Для восстановления пароля введите адрес электронной почты, указанный Вами при регистрации. На почту придет
            письмо с подтверждением о смене пароля.
        </p>
        <?php $form = ActiveForm::begin([
            'id' => 'forget',
            //'action' => 'users/registrate_do',
            'options' => [
                'class' => 'form-inline',
            ],
            'enableClientScript' => false,
            'fieldConfig' => [
                'template' => "{label}\n{input}{error}",
                'labelOptions' => [
                    'class' => 'sr-only',
                ],
            ],
        ])?>
            <?=$form->field($model, 'email', ['inputOptions' => [
                'placeholder' => $model->getAttributeLabel('email'),
                'class' => 'form-control',
            ]])?>

            <button type="submit" class="btn btn-blue" style="vertical-align: top;">Восстановить</button>
        <?php ActiveForm::end()?>
    </div>
</div>
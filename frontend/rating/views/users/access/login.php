<?php use frontend\rating\assets\FormValidationAsset;
use yii\widgets\ActiveForm;

$this->breadcrumbs = ['Авторизация'];
$this->title = 'Авторизация';
?>



    <div class="row">

        <div class="col-xs-6 col-xs-offset-3">
            <?php $form = ActiveForm::begin([
                'id' => 'registrate',
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
                    'options' => [
                        'class' => 'form-group',
                        'style' => 'vertical-align: top',
                    ],
                ],
            ])?>

                <?=$form->field($model, 'email', ['inputOptions' => [
                    'placeholder' => $model->getAttributeLabel('email'),
                    'class' => 'form-control',
                ]])?>

                <?=$form->field($model, 'password', ['inputOptions' => [
                    'placeholder' => $model->getAttributeLabel('password'),
                    'class' => 'form-control',
                ]])->passwordInput()?>

                <button type="submit" class="btn btn-blue" style="vertical-align: top;">Войти</button>
                <input style="display:none;" type="hidden" name="from_page" value="/users/applications/">
            <?php ActiveForm::end()?>
            <p>
                Если Вы еще не зарегистрированы на сайте, Вы можете
                <a href="<?=\yii\helpers\Url::toRoute('users/access/registration')?>" class="sub">зарегистрироваться</a>.
            </p>
            <p>
                Если Вы забыли пароль, Вы можете
                <a href="<?=\yii\helpers\Url::toRoute('users/access/password-recover')?>" class="sub">восстановить пароль</a>.
            </p>
        </div>
    </div>
<?php use frontend\rating\assets\FormValidationAsset;
use yii\widgets\ActiveForm;

$this->breadcrumbs = ['Регистрация'];
$this->title = 'Регистрация';
?>

    <div class="row">
        <div class="col-xs-6 col-xs-offset-3">
            <?php if ($acceptRegistration): ?> 
                <?php $form = ActiveForm::begin([
                    'id' => 'registrate',
                    //'action' => 'users/registrate_do',
                    'options' => [
                        'class' => 'dimox',
                        'data-plugin' => 'antispam'
                    ],
                    'enableClientScript' => true,
                    'fieldConfig' => [
                        'template' => "{input}\n{hint}\n{error}",
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

                    <?=$form->field($model, 'password_confirm', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('password_confirm'),
                        'class' => 'form-control',
                    ]])->passwordInput()?>

                    <?=$form->field($model, 'last_name', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('last_name'),
                        'class' => 'form-control',
                    ]])?>

                    <?=$form->field($model, 'first_name', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('first_name'),
                        'class' => 'form-control',
                    ]])?>

                    <?=$form->field($model, 'middle_name', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('middle_name'),
                        'class' => 'form-control',
                    ]])?>

                    <?=$form->field($model, 'org_name', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('org_name'),
                        'class' => 'form-control',
                    ]])?>

                    <?=$form->field($model, 'org_position', ['inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('org_position'),
                        'class' => 'form-control',
                    ]])?>

                    <div class="form-group">
                        <div class="row">
                            <?=$form->field($model, 'captcha', [
                                'template' => "{input}{error}",
                                'errorOptions' => ['class' => 'help-block col-xs-8'],
                            ])->widget(\frontend\rating\components\Captcha::class, [
                                    'captchaAction' => 'home/captcha',
                                    'name' => 'captcha',
                                    'model' => $model,
                                    'template' => '<div class="col-xs-8">{input}</div><p class="captcha-block">{image}</p>',
                                    'options' => [
                                        'class' => 'form-control',
                                        'placeholder' => $model->getAttributeLabel('captcha'),
                                    ],
                                ])
                            ?>
                        </div>

                    </div>

                    <div><p>Нажимая на кнопку «Зарегистрироваться», я даю <a href="/o-rejtinge/usloviya-uchastiya/assigment/" target="_blank">согласие на обработку персональных данных</a></p></div>
                    <input type="submit" class="btn btn-blue" value="Зарегистрироваться">

                <?php ActiveForm::end()?>
            <?php else: ?>
                Регистрация участников приостановлена
            <?php endif; ?>
        </div>
    </div>
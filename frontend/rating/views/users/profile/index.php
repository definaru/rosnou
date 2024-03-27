<?php use yii\widgets\ActiveForm;

$this->title = 'Профиль';
$this->breadcrumbs = ['Профиль пользователя'] ?>

<div class="row">
    <div class="col-xs-10 col-xs-offset-1">
        <div id="crop-avatar">
            <div class="row">
                <?=\chusov\cropper\Cropper::widget([
                    'popupTitle' => 'Загрузка аватара',
                    'content' => $this->render('_avatar', ['user' => $user]),
                    'exists' => $user->avatar_image ? true : false,
                ])?>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'registrate',
                'options' => [
                    'class' => 'form',
                ],
                'enableClientScript' => true,
                'fieldConfig' => [
                    'template' => "{input}\n{hint}\n{error}",
                ],
            ])?>
                <p class="uppercase">Персональная информация<span class="style"></span></p>
                <div class="row">
                    <div class="col-xs-6">
                        <?=$form->field($model, 'email', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('email'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'last_name', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('last_name'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'first_name', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('first_name'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'middle_name', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('middle_name'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'org_name', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('org_name'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'org_position', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('org_position'),
                            'class' => 'form-control',
                        ]])?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'password', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('password'),
                            'class' => 'form-control',
                        ]])->passwordInput()?>
                    </div>

                    <div class="col-xs-6">
                        <?=$form->field($model, 'password_confirm', ['inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('password_confirm'),
                            'class' => 'form-control',
                        ]])->passwordInput()?>
                    </div>
                </div>
                <button type="submit" class="btn btn-blue">Сохранить</button>

            <?php ActiveForm::end()?>
        </div>
    </div>
</div>

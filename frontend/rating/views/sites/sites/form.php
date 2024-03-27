<?php use yii\widgets\ActiveForm;
/** @var $model \frontend\rating\forms\SiteForm */
/** @var $this \frontend\rating\components\View */
$this->breadcrumbs = ['Добавление сайта'];
$this->title = $this->pageHeader = isset($site) ? 'Редактирование сайта'  : 'Добавление сайта';
?>

<?php $this->registerJs('APP.modules.addSiteForm.bindCountrySubjectDropdown();')?>
<?php $this->registerJs('APP.modules.addSiteForm.bindSubjectMoscow();')?>

<?php if(isset($site)):?>
    <?php foreach($siteComments as $comment):?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-<?=$comment->user_id == $site->user_id ? 'success' : 'default'?>">
                    <div class="panel-heading"><h4 class="panel-title"><?=$comment->user_id == $site->user_id ? 'Ваш комментарий' : 'Комментарий модератора'?></h4></div>
                    <div class="panel-body"><?=$comment->body?></div>
                </div>
            </div>
        </div>
    <?php endforeach?>
<?php endif?>

<div class="row">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin([
            'id' => 'site',
            'enableClientValidation' => false,
            'options' => [
                'class' => 'form',
            ],
            'fieldConfig' => [
                'template' => "{input}\n{hint}\n{error}",
            ],
        ]) ?>

        <div class="row">

            <div class="col-xs-6">
                <?=$form->field($model, 'type_id', [
                    'inputOptions' => [
                        'data-placeholder' => $model->getAttributeLabel('type_id'),
                        'class' => 'form-control rnselect',
                    ]
                ])->dropDownList($model->typeOptions())?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'domain', [
                    'inputOptions' => array_merge([
                        'placeholder' => $model->getAttributeLabel('domain'),
                        'class' => 'form-control',
                    ], isset($site) ? ['disabled' => 'disabled'] : []),
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'title', [
                    'inputOptions' => array_merge([
                        'placeholder' => $model->getAttributeLabel('title'),
                        'class' => 'form-control',
                    ], isset($site) ? ['disabled' => 'disabled'] : []),
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'org_title', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('org_title'),
                        'class' => 'form-control',
                    ]
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'district_id', [
                    'inputOptions' => [
                        'data-placeholder' => $model->getAttributeLabel('district_id'),
                        'class' => 'form-control rnselect',
                    ]
                ])->dropDownList($model->districtOptions())?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'subject_id', [
                    'inputOptions' => [
                        'data-placeholder' => $model->getAttributeLabel('subject_id'),
                        'class' => 'form-control rnselect',
                        'data-current-value' => $model->subject_id,
                    ]
                ])->dropDownList([])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'location', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('location'),
                        'class' => 'form-control',
                    ]
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'headname', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('headname'),
                        'class' => 'form-control',
                    ]
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'headname_email', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('headname_email'),
                        'class' => 'form-control',
                    ]
                ])?>
            </div>

            <div class="col-md-6">
                <div class="form-group dimox">
                    <p class="help-block"><?=$model->getAttributeLabel('have_ads')?></p>

                    <?=$form->field($model, 'have_ads', [
                            'template' => "{input}",
                        ])
                        ->radioList([1 => 'да', 0 => 'нет'],
                            ['itemOptions' => [
                                    'labelOptions' => [
                                        'class' => 'radio-inline',
                                    ],
                                ],
                            ]
                        )?>
                </div>
            </div>

        </div>

        <?php if(isset($site) AND !$site->isStatus(\common\models\Site::STATUS_ON_MODERATION)):?>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group has-feedback">
                        <textarea name="user_comment" class="form-control" placeholder="Для повторной подачи заявки, оставьте комментарий модератору" data-fv-field="user_comment"></textarea><i class="form-control-feedback fv-icon-no-label" data-fv-icon-for="user_comment" style="display: none;"></i><small class="help-block" data-fv-validator="notEmpty" data-fv-for="user_comment" data-fv-result="NOT_VALIDATED" style="display: none;">Необходимо добавить комментарий к заявке</small>
                    </div>
                </div>
            </div>

        <?php endif?>
        <div>
            <strong>Нажимая на кнопку СОХРАНИТЬ, я даю <a href='/o-rejtinge/usloviya-uchastiya/assigment/' target="_blank">согласие на обработку персональных данных</a></strong><br><br>
        </div>
        <a href="<?=\yii\helpers\Url::toRoute('sites/sites/index')?>" class="btn">К списку сайтов</a>
        <button type="submit" class="btn btn-blue">Сохранить</button>

        <?php ActiveForm::end() ?>
    </div>
</div>
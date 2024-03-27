<?php use yii\widgets\ActiveForm;

$this->breadcrumbs = ['Заявки'] ?>
<?php $this->title = 'Заявки' ?>

<?php if($error):?>
    <?=$error?>
<?php endif?>

<?php if($siteComments):?>
    <div class="row">
        <div class="col-xs-12">
            <?php foreach($siteComments as $comment):?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-<?=$comment->user_id != $site->user_id ? 'success' : 'default'?>">
                            <div class="panel-heading"><h4 class="panel-title"><?=$comment->user_id == $site->user_id ? 'Комментарий участника' : 'Комментарий модератора'?></h4></div>
                            <div class="panel-body"><?=$comment->body?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach?>
        </div>
    </div>
<?php endif?>

<h3><span class="small pull-right"><a href="<?=\yii\helpers\Url::toRoute('sites/sites/moderate-list')?>"
                                                           title="">вернуться к списку</a></span></h3>

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
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('domain'),
                        'class' => 'form-control',
                    ]
                ])?>
            </div>

            <div class="col-xs-6">
                <?=$form->field($model, 'title', [
                    'inputOptions' => [
                        'placeholder' => $model->getAttributeLabel('title'),
                        'class' => 'form-control',
                    ]
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
                    ]
                ])->dropDownList($model->subjectOptions())?>
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

        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#moderator_comment">Отклонить
        </button>
        <button type="submit" name="accept" value="1" class="btn btn-blue">Одобрить заявку</button>
        <button type="button" class="btn btn-blue" data-toggle="modal" data-target="#accept_comment">Одобрить заявку с
            комментарием
        </button>

        <div class="modal fade" id="moderator_comment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button><h4 class="modal-title" id="myModalLabel">Причина отказа</h4>
                    </div>
                    <div class="modal-body"><div class="row"><div class="col-xs-12"><div class="form-group"><textarea name="moderator_comment" rows="6" placeholder="Комментарий к заявке" class="form-control"></textarea></div></div></div></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Передумать</button>
                        <button type="submit" name="deny" value="1" class="btn btn-primary">Подтвердить</button>
                    </div>
                </div></div></div>

        <div class="modal fade" id="accept_comment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Одобрение заявки</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group"><textarea name="accept_comment" rows="6"
                                                                  placeholder="Комментарий к заявке"
                                                                  class="form-control"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Передумать</button>
                        <button type="submit" name="accept_with_comment" value="1" class="btn btn-primary">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>

<br />
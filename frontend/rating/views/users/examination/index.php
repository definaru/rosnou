<?php use yii\widgets\ActiveForm;

$this->title = 'Самоообследование' ?>

<div class="col-xs-12">
    <h2>Самообследование</h2>
    <br>
    <?php if($PeriodManager->AcceptRequest1($activePeriod)): ?>
        <div class="alert alert-info" role="alert">
            Напротив критерия разместите ссылку на страницу, содержащую информацию, удовлетворяющую данному критерию.
            Пожалуйста, не размещайте ссылок на документы, а также на страницы, недоступные в пределах 4 кликов от главной
            страницы
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'self-examination',
            //'action' => 'users/registrate_do',
            'options' => [
                'class' => 'form-horizontal fv-form fv-form-bootstrap',
            ],
            'enableClientScript' => false,
            //'fieldConfig' => [
            //    'template' => "{label}\n{input}{error}",
            //    'labelOptions' => [
            //        'class' => 'sr-only',
            //    ],
            //    'options' => [
            //        'class' => 'form-group',
            //        'style' => 'vertical-align: top',
            //    ],
            //],
        ]) ?>

        <button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>

        <?php foreach ($criteriaTypes as $type): ?>

            <div class="row">
                <div class="form-group">
                    <div class="col-sm-6"><h3 class="pull-right"><?=$type->title?></h3></div>
                    <div class="col-sm-6">&nbsp;</div>
                </div>
            </div>

            <?php if (isset($criterias[$type->id])): ?>

                <div class="row">

                    <?php foreach ($criterias[$type->id] as $criteria): ?>

                        <div class="form-group">
                            <label for="criteria-<?=$criteria->id?>"
                                   class="col-sm-6 control-label"><?=$criteria->title?></label>
                            <div class="col-sm-6 has-feedback">
                                <input name="criteria[<?=$criteria->id?>]" type="text" class="form-control"
                                       id="criteria-<?=$criteria->id?>" placeholder="http://" data-fv-field="input"
                                       value="<?=isset($criteriaResults[$criteria->id]) ? $criteriaResults[$criteria->id]->url : ''?>"
                                ><i class="form-control-feedback fv-icon-no-label" data-fv-icon-for="input"
                                        style="display: none;"></i>
                                <small class="help-block" data-fv-validator="regexp" data-fv-for="input"
                                       data-fv-result="NOT_VALIDATED" style="display: none;">Введен некорректный адрес
                                    страницы
                                </small>
                            </div>
                        </div>

                    <?php endforeach ?>

                </div>

            <?php endif ?>

        <?php endforeach ?>

        <div class="modal fade" id="accept-self-examination" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Завершение самообследования</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                Подтверждаете ли вы отправку вашего самообследования эксперту?
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Передумать</button>
                        <button type="submit" class="btn btn-primary">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group text-right">
                <div class="col-xs-12">
                    <button type="button" class="btn btn-blue" data-toggle="modal" data-target="#accept-self-examination">
                        Завершить самообследование
                    </button>
                    &nbsp;
                    <button type="submit" name="do_draft" value="1" class="btn btn-default">
                        Сохранить черновик
                    </button>
                </div>
            </div>
        </div>
        <?php ActiveForm::end() ?>
<?php else: ?>
    Прием первичных заявок на самообследование приостановлен        
<?php endif; ?>
</div>

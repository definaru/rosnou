
<?php 
use common\models\RateRequest;
use common\models\RateCriteria;
use \common\models\RateCriteriaResult;


$this->title = 'Экспертиза'
?>

<div class="row">
    <div class="col-xs-12">
        <h2>Экспертиза</h2>
        <div><h3><?=$rateRequest->site->title?></h3></div>
        <div>
            <span class="uppercase">Категория сайта:</span>&nbsp;<?=$rateRequest->site->type->title?>
        </div>
        <div>
            <span class="uppercase">Адрес сайта:</span>&nbsp;<a href="<?=$rateRequest->site->domain?>"><?=$rateRequest->site->domain?></a>
        </div>
        <form action="" method="post"
              class="form-horizontal dimox fv-form fv-form-bootstrap" id="examination" novalidate="novalidate">
            <button type="submit" class="fv-hidden-submit" style="display: none; width: 0px; height: 0px;"></button>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-6">&nbsp;</div>
                    <div class="col-sm-2">&nbsp;</div>
                    <div class="col-sm-3"><h3>Самообследование</h3></div>
                    <div class="col-sm-1">&nbsp;</div>
                </div>
            </div>

            <?php foreach($results as $type):?>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-6"><h3 class="pull-right"><?= $type['title'] ?></h3></div>
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-3">&nbsp;</div>
                        <div class="col-sm-1">&nbsp;</div>
                    </div>
                </div>

                <div class="row">

                <?php foreach($type['criteriaList'] as $criteria):?>

                    <?php //if( !$criteria['result'] ) continue ?>

                    <?php $result = $criteria['result'] ?>

                    <div class="row form-group">

                        <div class="col-sm-6">
                            <div class="vertical-box pull-right">
                                <label for="criteria-<?=$criteria['id']?>" class="control-label"><?=$criteria['title'] ?></label>
                            </div>
                        </div>

                        <div class="col-sm-2">

                            <?php 
                            // Радиогруппа
                            if( $criteria['field_type'] == RateCriteria::CRITERIA_TYPE_LIST ): ?>
                            
                            <label class="radio-inline">
                                <input data-score="<?= $criteria['score'] ?>" type="radio" <?= $result['checkedYes'] ? ' checked="checked"' : ''?> name="criteria[<?=$criteria['field_name']?>]" value='<?= json_encode(['id' => $criteria['id'], 'value' => 1]) ?>' data-fv-notempty="true" data-fv-message="Выберите">
                            </label>

                            <?php 
                            // Обычный checkbox
                            else: ?>

                            <label class="radio-inline">
                                <input data-score="<?= $criteria['score'] ?>" type="radio" <?= $result['checkedYes'] ? ' checked="checked"' : ''?> name="criteria[<?=$criteria['id']?>]" value='<?= json_encode(['id' => $criteria['id'], 'value' => 1]) ?>' data-fv-notempty="true" data-fv-message="Выберите Да или Нет">
                                да
                            </label>

                            <label class="radio-inline">
                                <input data-score="0" type="radio" <?= $result['checkedNo'] ? ' checked="checked"' : ''?> name="criteria[<?=$criteria['id']?>]" value='<?= json_encode(['id' => $criteria['id'], 'value' => 0]) ?>' data-fv-notempty="true" data-fv-message="Выберите Да или Нет">
                                нет
                            </label>

                            <?php endif ?>
                        </div>
                        
                        <div class="col-sm-3"><a href="<?= $result['url'] ?>" target="_blank"><?= $result['url'] ?></a></div>
                        
                        <div class="col-sm-1"><button class="btn btn-primary ticket-open" type="button" data-toggle="modal" data-target="#tickets" data-criteria="<?=$criteria['id']?>" data-result="<?= $result['id'] ?>">тикеты
                                <?php if($result['comment_count'] > 0):?>
                                    <span class="badge badge-default"><?=$result['comment_count'] ?></span>
                                <?php endif?>
                            </button>
                        </div>

                    </div>

                    <?php if( $result['function_data'] ): ?>
                    <?php foreach($result['function_data'] as $data): ?>
                    <div class="row form-group">
                            <div class="col-sm-6"><div class="vertical-box pull-right"><?= $data['title'] ?></div></div>
                            <div class="col-sm-2"><?= $data['value'] ?></div>
                            <div class="col-sm-4"></div>
                    </div>
                    <?php endforeach ?>
                    <?php endif ?>

                <?php endforeach?>

                </div>

            <?php endforeach?>

            <br>
            <div class="row">
                <div class="col-xs-6"><h2>Набрано баллов:
                        <span class="label label-info" id="summary">0</span>
                    </h2></div>
                <div class="col-xs-6">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-blue" onclick="$('[data-dodraft]').val(0);">
                            Завершить экспертизу
                        </button>
                        &nbsp;
                        <button type="submit" class="btn btn-default"
                                formnovalidate="formnovalidate">
                            Сохранить черновик
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="type_id" value="136">
            <input data-dodraft type="hidden" name="do_draft" value="1">

        </form>
        <script type="text/javascript" async="" src="https://mc.yandex.ru/metrika/watch.js"></script>
        <script type="text/x-tmpl" id="tmpl-demo">

					{% for (var i=0; i<o.length; i++) { %}
						{% if (o[i].answer) { %}
                            <div class="message right">
                                <img src="{%=o[i].avatar%}" alt="" class="message-avatar" />
                                <div class="message-body">
                                    <div class="message-heading">
                                        <a href="#" title="">{%=o[i].author%}</a>
                                        пишет:
                                        <span class="pull-right">{%=o[i].publish_time%}</span>
                                    </div>
                                    <div class="message-text">
                                        {%=o[i].content%}
                                    </div>
                                </div>
                            </div>
						{% } else { %}
                            <div class="message">
                                <img src="{%=o[i].avatar%}" alt="" class="message-avatar" />
                                <div class="message-body">
                                    <div class="message-heading">
                                        <a>Вы</a>
                                        пишете:
                                        <span class="pull-right">{%=o[i].publish_time%}</span>
                                    </div>
                                    <div class="message-text">
                                        {%=o[i].content%}
                                        <button type="button" data-id="{%=o[i].id%}" class="close" aria-label="Удалить"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                </div>
                            </div>
						{% } %}
					{% } %}


        </script>

        <div class="modal fade widget-chat" id="tickets" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <i class="panel-title-icon fa fa-comments-o"></i> Тикеты</h4>
                        <div class="well well-sm">
                            <small id="panel-descr"></small>
                        </div>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <form class="chat-controls" method="post" action="<?=\yii\helpers\Url::toRoute('/users/tickets/do')?>" id="ticket-form">
                            <div class="chat-controls-input"><textarea name="message" rows="5" class="form-control"
                                                                       placeholder="Напишите сообщение.."></textarea>
                            </div>
                            <button class="btn btn-primary chat-controls-btn" type="submit"
                                    data-loading-text="Отправка...">Отправить
                            </button>
                            <input type="hidden" name="result_id">
                            <input type="hidden" name="criteria_id">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
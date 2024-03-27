<?php $this->title = 'Результаты экспертизы' ?>

<div id="singletopic" class="section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>Результаты экспертизы</h2>
                <form action="<?=\yii\helpers\Url::toRoute(['users/examination/index', 'id' => $site->id])?>" method="post" class="form-horizontal dimox"
                      id="examination">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-1"><h3>Баллы</h3></div>
                            <div class="col-sm-5"><h3>Самообследование</h3></div>
                            <div class="col-sm-1"><h3>Тикеты</h3></div>
                        </div>
                    </div>

                    <?php foreach ($criteriaTypes as $type): ?>

                        <div class="row">
                            <div class="form-group">
                                <div class="col-sm-5"><h3><?=$type->title?></h3></div>
                                <div class="col-sm-1"></div>
                                <div class="col-sm-5"></div>
                                <div class="col-sm-1"></div>
                            </div>
                        </div>

                        <?php if (isset($criterias[$type->id])): ?>

                            <?php foreach ($criterias[$type->id] as $criteria): ?>

                                <?php if(!isset($criteriaResults[$criteria->id])) continue?>

                                <?php $result = $criteriaResults[$criteria->id]?>

                                <div class="row">
                                    <div class="col-sm-5"><label for="criteria-<?=$criteria->id?>"><?=$criteria->title?></label></div>
                                    <div class="col-sm-1">
                                        <h4>
                                            <div class="label label-<?=$result->isYes() ? 'info' : 'danger'?>"><?=$result->isYes() ? $criteria->score : 0?></div>
                                        </h4>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <?php if (!$type->hidden_flag): ?>
                                                <input name="criteria[<?=$criteria->id?>]" value="<?=$result->url?>"
                                                                           type="text" class="form-control" id="criteria-<?=$criteria->id?>"
                                                                           placeholder="http://">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-primary ticket-open" type="button" data-toggle="modal"
                                                data-target="#tickets" data-criteria="<?=$criteria->id?>" data-result="<?=$result->id?>">
                                            Открыть

                                            <?php if($result->comment_count > 0):?>
                                                <span class="badge badge-default"><?=$result->comment_count?></span>
                                            <?php endif?>
                                        </button>
                                    </div>
                                </div>

                            <?php endforeach ?>

                        <?php endif ?>

                    <?php endforeach ?>

                    <br>
                    <xsl>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if($PeriodManager->AcceptRequest2($activePeriod)): ?>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-blue">
                                            Отправить на повторную экспертизу
                                        </button>
                                    </div>
                                <?php else: ?>
                                    Прием повторных заявок на самообследование приостановлен
                                <?php endif; ?>
                            </div>
                        </div>
                    </xsl>
                    <input type="hidden" name="self_examination_id" value="38754">
                </form>
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
                <div class="modal fade widget-chat" id="tickets" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
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
                                <form class="chat-controls" method="post" action="<?=\yii\helpers\Url::toRoute('users/tickets/do')?>" id="ticket-form">
                                    <div class="chat-controls-input"><textarea name="message" rows="5"
                                                                               class="form-control"
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
    </div>
</div>

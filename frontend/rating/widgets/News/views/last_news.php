<div class="section20 graylight">
    <div id="maintopics">
        <div class="container">
            <h3>Новости<span class="pull-right small"><a href="<?=\yii\helpers\Url::toRoute('/news/list')?>" title="">Все новости</a></span>
            </h3>
            <div class="row">
                <?php foreach($mainNews as $item):?>
                    <div class="col-xs-6">
                        <div class="topic-item">
                            <div class="topic">
                                <div class="topic-thumb"></div>
                                <div class="topic-entry box20">
                                    <div class="topic-name"><a
                                            href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>"
                                            title=""><?=$item->title?></a></div>
                                    <div class="date"><?=Yii::$app->formatter->asDate($item->publish_date)?></div>
                                    <div class="bambas">
                                        <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="topic-overlay">
                                <div class="overlay-entry box20">
                                    <div class="topic-name"><a
                                            href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>"
                                            title=""><?=$item->title?></a></div>
                                    <div class="">
                                        <?=$item->preview?>
                                    </div>
                                    <div class="date">
                                        <?=Yii::$app->formatter->asDate($item->publish_date)?>
                                    </div>
                                    <div class="bambas">
                                        <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
            </div>
        </div>
    </div>
    <div id="topics" class="section">
        <div class="container">
            <div class="row">
                <div id="" class="masonry-news">
                    <?php foreach($others as $item):?>

                        <div class="col-md-3 col-sm-4 col-xs-6 masonry-item">
                            <div class="topic-item">
                                <div class="topic">
                                    <div class="topic-thumb"></div>
                                    <div class="topic-entry box20">
                                        <div class="topic-name"><a
                                                    href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>"
                                                    title=""><?=$item->title?></a></div>
                                        <div class="date"><?=Yii::$app->formatter->asDate($item->publish_date)?></div>
                                        <div class="bambas">
                                            <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i
                                                    class="fa fa-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="topic-overlay">
                                    <div class="overlay-entry box20">
                                        <div class="topic-name"><a
                                                    href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>"
                                                    title=""><?=$item->title?></a></div>
                                        <div class="">
                                            <?=$item->preview?>
                                        </div>
                                        <div class="date"><?=Yii::$app->formatter->asDate($item->publish_date)?></div>
                                        <div class="bambas">
                                            <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i
                                                    class="fa fa-circle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach;?>

                </div>
            </div>
        </div>
    </div>
</div>
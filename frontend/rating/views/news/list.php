<?php $this->title = $pageName ?>

<div class="row"><div id="" class="masonry-news" style="position: relative; height: 627px;">
    <?php foreach($news as $item):?>

        <div class="col-md-3 col-sm-4 col-xs-6 masonry-item" style="position: absolute; left: 0px; top: 0px;"><div class="topic-item">
                <div class="topic">
                    <div class="topic-thumb"></div>
                    <div class="topic-entry box20">
                        <div class="topic-name"><a href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>" title=""><?=$item->title?></a></div>
                        <div class="date"><?=Yii::$app->formatter->asDate($item->publish_date)?></div>
                        <div class="bambas">
                            <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="topic-overlay"><div class="overlay-entry box20">
                        <div class="topic-name"><a href="<?=\yii\helpers\Url::toRoute(['/news/view', 'slug' => $item->slug])?>" title=""><?=$item->title?></a></div>
                        <div class=""><?=$item->preview?></div>
                        <div class="date"><?=Yii::$app->formatter->asDate($item->publish_date)?></div>
                        <div class="bambas">
                            <i class="fa fa-circle"></i><i class="fa fa-circle"></i><i class="fa fa-circle"></i>
                        </div>
                    </div></div>
            </div>
        </div>

    <?php endforeach?>
</div>
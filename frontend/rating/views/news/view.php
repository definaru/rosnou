<?php $this->title = $pageName ?>
<?php $this->breadcrumbs = ['label' => $pageName]?>

<?php \frontend\rating\assets\NewsAsset::register($this) ?>

<div id="singletopic" class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 hidden-xs">
                <div class="topic-thumb inner-thumb">
                </div>
            </div>
            <div class="col-sm-9 col-xs-12">
                <div class="row">
                    <div class="col-sm-4 col-xs-12 pull-right"><h3><span class="pull-right small"><a
                                        href="<?=\yii\helpers\Url::toRoute('/news/list')?>"
                                        title="">вернуться к списку</a></span></h3></div>
                    <div class="col-sm-8 col-xs-12 topic-title">
                        <h2><?=$news->title?></h2>
                        <div class="date"><?=Yii::$app->formatter->asDate($news->publish_date)?></div>
                    </div>
                </div>
                <div id="content">
                    <?=$news->content?>
                </div>
                <div class="share">
                    <button type="submit" class="btn btn-fb goodshare" data-type="fb"><i
                                class="fa fa-facebook"></i><span data-counter="fb">0</span></button>
                    <button type="submit" class="btn btn-vk goodshare" data-type="vk"><i class="fa fa-vk"></i><span
                                data-counter="vk">0</span></button>
                    <button type="submit" class="btn btn-ok goodshare" data-type="ok"><i
                                class="fa fa-odnoklassniki"></i><span data-counter="ok">0</span></button>
                    <button type="submit" class="btn btn-goo goodshare" data-type="gp"><i class="fa fa-google-plus"></i><span
                                data-counter="gp">0</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
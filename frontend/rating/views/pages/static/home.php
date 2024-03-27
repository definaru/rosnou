<?php $this->title = 'Главная' ?>


<div id="archives" class="header-links section20 white wow fadeInLeft" data-wow-delay="1.5s">
    <div class="container"></div>
</div>

<div id="mapregions" class="graylight hidden-xs">
    <div class="container">
        <div id="vmap"></div>
        <div class="col-xs-3">
            <div class="panel stat-panel">
                <div class="panel-body">
                    <div class="stat"><?= $list['totalCountSubjects']['count'] ?>
                        <small><?= $list['totalCountSubjects']['text'] ?></small>
                    </div>
                    <div class="dotted-divider"></div>
                    <div class="stat"><?= $list['totalCountSites']['count'] ?>
                        <small><?= $list['totalCountSites']['text'] ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="banners" class="section20">
    <div class="container">
        <div class="row clearfix">
            <div class="col-xs-4"><a href="http://rating.rosnou.ru/"><img class="banner img-responsive"
                                                                          src="<?=$this->img('banners/archive.png')?>" alt=""></a>
            </div>
            <div class="col-xs-4"><a href="/o-rejtinge/smi-o-rejtinge/"><img class="banner img-responsive"
                                                                             src="<?=$this->img('banners/smioreitinge.png')?>"
                                                                             alt=""></a></div>
        </div>
    </div>
</div>

<?=\frontend\rating\widgets\News\LastNews::widget()?>

<?=$this->render('/partials/info_partners')?>
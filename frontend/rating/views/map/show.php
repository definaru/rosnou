
<?php \frontend\rating\assets\MapAsset::register($this) ?>
<?php
    $this->title = 'Главная'
?>


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
<div class="container">
    <?= $section->body; ?>
</div>

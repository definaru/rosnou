<?php if( sizeof($banners) > 0 ): ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div align="center" class="well">
                <h2 class="text-center">
                    Информационные партнеры
                </h2>
                <div class="row clearfix">
                    <?php foreach ($banners as $banner) : ?>
                    <div class="col-xs-12 col-sm-4">
                        <a href="<?= $banner->url ?>" target="_blank">
                            <img alt="" class="banner img-responsive" src="<?= $banner->image_file ?>"/>
                        </a>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
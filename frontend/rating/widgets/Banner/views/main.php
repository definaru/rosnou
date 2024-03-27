<?php if( sizeof($banners) > 0 ): ?>
<div class="section20" id="banners">
    <div class="container">
        <div class="row clearfix">
            <?php foreach ($banners as $banner) : ?>
            <div class="col-xs-12 col-sm-4">
                <a href="<?= $banner->url ?>">
                    <img alt="" class="banner img-responsive" src="<?= $banner->image_file ?>">
                </a>
            </div>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php endif ?>

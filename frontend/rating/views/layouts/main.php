<?php

use yii\helpers\Html;
use yii\helpers\Url;


//$bundle = frontend\rating\assets\SupportMainAsset::register($this);
$this->
beginPage();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?=$this->
        render('/partials/meta')?>
        <?=Html::csrfMetaTags()?>
        <?php $this->
        head() ?>
        <title>
            <?=Html::encode($this->
            title)?> | Общероссийский рейтинг образовательных сайтов
        </title>
    </head>
    <body>
        <?php $this->
        beginBody() ?>
        <?=$this->
        render('/partials/header')?>
        <?=$this->
        render('/partials/nav')?>
        <?=$content?>
        <?php if (Url::to() == '/'): ?>
        <?=\frontend\rating\widgets\Banner\Banner::widget(['template' => 'main', 'type' => \common\models\Banner::BANNER_MAIN_PAGE ]) ?>

        <?=\frontend\rating\widgets\News\LastNews::widget()?>
        <?php endif; ?>
        <?=$this->render('/partials/info_partners')?>
        <?=$this->render('/partials/footer')?>
        <?php if(Yii::getAlias('@show_metrika')):?>
        <?=$this->render('/partials/scripts')?>
        <?php endif?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

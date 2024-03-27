<?php use frontend\rating\components\Breadcrumbs;

$this->beginContent('@app/views/layouts/main.php') ?>

    <div id="breadcrumbs" class="header-links white">
        <div class="container">
            <?=Breadcrumbs::widget([
                'links' => $this->breadcrumbs,
            ])?>
        </div>
    </div>

    <?php if($this->pageHeader):?>
        <div id="headerpic" class="bluedark">
            <div class="gradient">
                <div class="container">
                    <h1><?=$this->pageHeader?></h1>
                    <?php if(isset($this->blocks['pageHeaderContent'])):?>
                        <?=$this->blocks['pageHeaderContent']?>
                    <?php endif?>
                </div>
            </div>
        </div>
    <?php endif?>

    <div id="singletopic" class="section">
        <div class="container">
            <?=$content?>
        </div>
    </div>

    <?php if(isset($this->blocks['underContent'])):?>
        <?=$this->blocks['underContent']?>
    <?php endif?>

<?php $this->endContent() ?>



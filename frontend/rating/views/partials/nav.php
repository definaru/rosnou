<?php 
$model = new frontend\rating\forms\SearchForm();
?>

<div id="main-nav" class="white">
    <div class="container">
        <div class="bambas">
            <div class="row">
                <div class="col-xs-8 col-sm-10 col-xs-offset-2 col-sm-offset-1">
                    <div class="navbar">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#mainmenu"><span class="sr-only">Toggle navigation</span>
                                Навигация
                            </button>
                        </div>
                        <div class="collapse navbar-collapse" id="mainmenu">
                            <ul class="nav navbar-nav">
                                <li>
                                    <a href="/o-rejtinge/" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-haspopup="true" aria-expanded="false">О рейтинге</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/o-rejtinge/istoriya/">История</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/novosti/">Новости</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/smi-o-rejtinge/">СМИ о рейтинге</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/usloviya-uchastiya/">Условия участия</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/partnery/">Партнеры</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/instruction/">Инструкции</a></li>
                                        <li role="divider">
                                        <li><a href="/o-rejtinge/konkurs/">Конкурс «БЛОГуч-2023»</a></li>

                                    </ul>
                                </li>
                                <li><a href="/kriterii/">Критерии</a></li>
                                <li><a href="/uchastniki/">Участники</a></li>
                                <li><a href="/rezults/">Результаты</a></li>
                                <li><a href="/kontakty/">Контакты</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?=$this->render('nav/user_nav')?>

            <div class="btn-search">
                <button type="button" id="" data-toggle="collapse" data-target="#searchform" aria-expanded="false"
                        aria-controls="searchform" class="btn-white btn-round"><i class="fa fa-search"></i></button>
            </div>
            <div id="searchform" class="collapse">
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'searh-form',
                    'action' => '/search/search_do/',
                    'method' => 'get',
                    'options' => ['class' => 'form-horizontal'],
                ]) ?>
                    <?= $form->field($model, 'query')->label(false); ?>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
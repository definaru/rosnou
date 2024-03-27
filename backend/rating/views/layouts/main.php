<?php
use backend\rating\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->params['project.name'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        $menuItems = [];


        $menuItems[] = ['label' => Yii::t('app', 'Периоды'), 'url' => ['/rate/rate-period/index']];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['user/access/login']];
        } else {
            $menuItems[] = [
                'label' => Yii::t('app', 'Пользователи'),
                'items' => [
                    ['label' => Yii::t('app', 'Пользователи'), 'url' => ['/user/user/index']],
                    ['label' => Yii::t('app', 'Уведомления'), 'url' => ['/notify/index']],
                ],
            ];

            $menuItems[] = [
                'label' => Yii::t('app', 'Контент'),
                'items' => [
                    ['label' => Yii::t('app', 'Новости'), 'url' => ['/news/index']],
                    ['label' => Yii::t('app', 'Разделы'), 'url' => ['/tree/default/index']],
                    ['label' => Yii::t('app', 'Баннеры'), 'url' => ['/banner/index']],
                ],
            ];

            //$menuItems[] = ['label' => Yii::t('app', 'Структура сайта'), 'url' => ['/tree/default/index']];
            //$menuItems[] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['/user/user/index']];
            //$menuItems[] = ['label' => Yii::t('app', 'Новости'), 'url' => ['/news/index']];

            $menuItems[] = [
                'label' => Yii::t('app', 'Сайты'),
                'items' => [
                    ['label' => Yii::t('app', 'Сайты'), 'url' => ['/site/site/index']],
                    ['label' => Yii::t('app', 'Типы'), 'url' => ['/site/type/index']],
                    ['label' => Yii::t('app', 'Субъекты РФ'), 'url' => ['/site/subject/index']],
                    ['label' => Yii::t('app', 'Округа'), 'url' => ['/site/district/index']],
                ],
            ];

            $menuItems[] = [
                'label' => Yii::t('app', 'Logout'). ' (' . Yii::$app->user->identity->firstname . ')',
                'url' => ['user/access/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);

        NavBar::end();
        ?>


        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>


    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Yii::$app->params['project.name'] ?> <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
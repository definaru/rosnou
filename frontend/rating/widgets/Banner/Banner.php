<?php

namespace frontend\rating\widgets\Banner;

use Yii;
use frontend\rating\managers\BannerManager;

class Banner extends \yii\base\Widget
{
    public $template = 'main';
    public  $type = \common\models\Banner::BANNER_MAIN_PAGE;

    /**
     * Запуск виджета
     */
    public function run() {
        $BannerManager = \Yii::$container->get(BannerManager::class);
        $listBanners = $BannerManager->loadBanners($this->type);

        return $this->render($this->template, ['banners' => $listBanners]);
    }
}

<?php
namespace frontend\rating\managers;

use Yii;
use common\models\Banner;

class BannerManager {

    /**
     * Извлекаем список баннеров по типу
     */
    public function loadBanners($bannerType = Banner::BANNER_MAIN_PAGE){
        
        $list = Banner::find()
            ->where([
                'type_index' => $bannerType,
            ])
            ->orderBy('list_order')
            ->all();

        return $list;
    }
}

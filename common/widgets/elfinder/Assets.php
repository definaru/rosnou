<?php

namespace common\widgets\elfinder;

use yii\web\JqueryAsset;

class Assets extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/widgets/elfinder/assets';

    public $css = array(
        ['css/elfinder.min.css'],
        ['css/theme.css'],
    );
    public $js = array(
        ['js/jquery.scrollTo-min.js'],
        ['js/elfinder.full.js'],
        ['js/i18n/elfinder.ru.js'],
    );

    public $depends = array(
        'yii\jui\JuiAsset',
    );

	/**
	 * @param string $lang
	 * @param \yii\web\View $view
	 */

	public static function addLangFile($lang, $view){

	}


	/**
	 * @param \yii\web\View $view
	 */

	public static function noConflict($view){
 
	}

}

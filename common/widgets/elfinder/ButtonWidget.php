<?php
/*
 *
 */

namespace common\widgets\elfinder;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class ButtonWidget
 *
 * @package common\widgets\elfinder
 */
class ButtonWidget extends Widget{
	public $language;

	public $filter;

	public $tag = 'button';
	public $name = 'Browse';
	public $options = [];

	protected $_managerOptions;

	public $width = 'auto';
	public $height = 'auto';

	public $template = '{input}{button}';

	public $controller = 'elfinder';

	public $path; // work with PathController

	public $callback;

	public function init()
	{
		parent::init();

		if(empty($this->language))
			$this->language = ElFinder::getSupportedLanguage(\Yii::$app->language);

		if(empty($this->options['id']))
			$this->options['id'] = $this->getId();
		else
			$this->setId($this->options['id']);

		$managerOptions = [];
		if(!empty($this->filter))
			$managerOptions['filter'] = $this->filter;

		$managerOptions['callback'] = $this->getId()."_manager";

		if(!empty($this->language))
			$managerOptions['lang'] = $this->language;

		if(!empty($this->path))
			$managerOptions['path'] = $this->path;

		$this->_managerOptions['url'] = ElFinder::getManagerUrl($this->controller, $managerOptions);
		$this->_managerOptions['width'] = $this->width;
		$this->_managerOptions['height'] = $this->height;
		$this->_managerOptions['id'] = $managerOptions['callback'];
	}

	public function run(){
		AssetsCallBack::register($this->getView());

		echo Html::tag($this->tag, $this->name, $this->options);

		$this->getView()->registerJs("mihaildev.elFinder.register(" . Json::encode($this->_managerOptions['id']) . ", " . Json::encode($this->callback) . ");"); // register callback Function
		$this->getView()->registerJs("\$(document).on('click', '#" . $this->options['id'] . "', function(){mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");});");//on click button open manager
	}
}

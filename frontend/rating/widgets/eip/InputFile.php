<?php

namespace frontend\rating\widgets\eip;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

// File input widget
use common\widgets\elfinder\ElFinder;
use yii\web\JsExpression;

// popup
use yii\bootstrap\Modal;

// assets
use common\widgets\elfinder\AssetsCallBack;

class InputFile extends InputWidget{

    public $language;
    public $filter;

    public $buttonTag = 'button';
    public $buttonName = 'Browse';
    public $buttonOptions = [];

    protected $_managerOptions;

    public $width = 'auto';
    public $height = 'auto';

    public $template = '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div><div style="margin-top:10px">{image}</div>';

    public $controller = 'elfinder';

    public $path; // work with PathController

    public $multiple=1;

    public $show_preview = true;

    public function init()
    {
        parent::init();

        if (empty($this->language))
            $this->language = ElFinder::getSupportedLanguage(Yii::$app->language);

        if (empty($this->buttonOptions['id']))
            $this->buttonOptions['id'] = $this->options['id'] . '_button';

        $this->buttonOptions['type'] = 'button';

        $managerOptions = [];


        if(!empty($this->filter))
            $managerOptions['filter'] = $this->filter;

        $managerOptions['callback'] = $this->options['id'];

        if(!empty($this->language))
            $managerOptions['lang'] = $this->language;

        if (!empty($this->multiple))
            $managerOptions['multiple'] = $this->multiple;
        if(isset(Yii::$app->controller->section) && isset(Yii::$app->controller->section['id'])){
            $this->path = Yii::getAlias('@upload/section/'.Yii::$app->controller->section['id']);
        }
        if(!empty($this->path))
            $managerOptions['path'] = $this->path;

        $this->_managerOptions['url'] = ElFinder::getManagerUrl($this->controller, $managerOptions);
        $this->_managerOptions['width'] = $this->width;
        $this->_managerOptions['height'] = $this->height;
        $this->_managerOptions['id'] = $this->options['id'];
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        // Print elfinder widget in modal
         Modal::begin([
            //'header' => '<h2>Hello world</h2>',
            'toggleButton' => false,
            'id' => $this->options['id'] . '-dialog',
            'size' => Modal::SIZE_LARGE,
        ]);

        echo ElFinder::widget([
            'language'         => $this->language,
            'controller'       => $this->controller, // вставляем название контроллера, по умолчанию равен elfinder
            'path' => $this->path, // будет открыта папка из настроек контроллера с добавлением указанной под деритории
            'filter'           => $this->filter,    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
            'callbackFunction' => new JsExpression('function(file, id){
                $("#' . $this->options['id'] . '").val( file.url );
                $("#' . $this->options['id'] . '-thumb").attr("src", file.url ).show();
                $("#' . $this->options['id'] . '-dialog").modal("hide");

            }'), // id - id виджета

            'path' => $this->path,
            'frameOptions' => ['style' => 'width: 100%; height: 500px; border: 0px;']
        ]);

        Modal::end();

        // Render input and upload button
        if ($this->hasModel()) {
            $attr = $this->attribute;
            $hidden = $this->model->$attr ? '' : 'display:none;';
            $replace['{image}'] = '<img id="' . $this->options['id'] . '-thumb" class="thumbnail" src="'.$this->model->$attr.'" style="max-width: 150px; max-height: 150px; '.$hidden.'" />';
            $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $hidden = $this->value ? '' : 'display:none;';
            $replace['{image}'] = '<img id="' . $this->options['id'] . '-thumb" src="'.$this->value.'"  style="max-width: 150px; max-height: 150px; '.$hidden.'" />';
            $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
        }

        if( !$this->show_preview ){
            $replace['{image}'] = '';
        }

        $replace['{button}'] = Html::tag($this->buttonTag,$this->buttonName, $this->buttonOptions);

        echo strtr($this->template, $replace);

        // Publish assets
        AssetsCallBack::register($this->getView());

        /*
        if (!empty($this->multiple)){

            $this->getView()->registerJs("

            mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ",
                function(files, id){
                    var _f = [];
                    for (var i in files) { _f.push(files[i].url); }
                    \$('#' + id).val(_f.join(', ')).trigger('change');
                    return true;
                });

            $(document).on('click','#" . $this->buttonOptions['id'] . "',
                function(){
                    mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");
                }
            );");

        } else {
        */

            $this->getView()->registerJs("

                mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ", function(file, id){
                    \$('#' + id).val(file.url).trigger('change');
                    return true;
                });

                $(document).on('click',
                    '#" . $this->buttonOptions['id'] . "',
                    function(){

                        var button = \$(this);
                        var input = button.parents('.input-group').find('input');
                        var imagePath = input.val();

                        // Обновляем содержимое редактора
                        // нужно вычислять правильный редактор
                        // иначе при наличии нескольких редакторов на одной страничке
                        // подмена содержимого может произойти не на том окне
                        var iframe = \$('#{$this->options['id']}-dialog').find('iframe');

                        var src  = iframe.attr('data-src');

                        var newsrc = src.split('#').shift().replace('/elfinder/manager', '/elfinder/xmanager') + '&file_path=' +  encodeURIComponent( imagePath );

                        // set iframe path
                        iframe.attr('src', newsrc );

                        $('#" . $this->options['id'] . "-dialog').modal('show');
                    }
                );");
        //}
    }
}

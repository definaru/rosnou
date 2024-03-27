<?php

namespace backend\rating\widgets;

use dosamigos\tinymce\TinyMce;
use yii\web\JsExpression;

// popup
use common\widgets\elfinder\ElFinder;
use yii\bootstrap\Modal;

class Editor extends TinyMce
{
    /**
     * @var string
     */
    public $language = 'ru';

    /**
     * @var array
     */
    public $options = [
        'rows' => 26,
    ];

    public function init(){

        $this->options['id'] = $this->id;

        $this->clientOptions = [

            'menubar' => false,

            //'file_picker_callback' => \alexantr\elfinder\TinyMCE::getFilePickerCallback(['fm/input?id=' . $this->id]),

            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen image",
                "insertdatetime media table contextmenu paste textcolor"
            ],
            'toolbar' => "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image code | styleselect forecolor fontsizeselect"
        ];

        /*
        // Print elfinder widget in modal
         Modal::begin([
            //'header' => '<h2>Hello world</h2>',
            'toggleButton' => false,
            'id' => $this->id . '-dialog',
            'size' => Modal::SIZE_LARGE,
        ]);

        echo ElFinder::widget([
            'language'         => $this->language,
            //'controller'       => $this->controller, // вставляем название контроллера, по умолчанию равен elfinder
            'path' => '', //$this->path, // будет открыта папка из настроек контроллера с добавлением указанной под деритории
            //'filter'           => $this->filter,    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
            'callbackFunction' => new JsExpression('function(file, id){
                $("#' . $this->id . '").val( file.url );
                $("#' . $this->id . '-thumb").attr("src", file.url ).show();
                $("#' . $this->id . '-dialog").modal("hide");

            }'), // id - id виджета

            'frameOptions' => ['style' => 'width: 100%; height: 500px; border: 0px;']
        ]);

        Modal::end();

        parent::init();
        */

    }
}
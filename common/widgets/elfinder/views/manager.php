<?php
/**
 * @var \yii\web\View $this
 * @var array $options
 */
use common\widgets\elfinder\Assets;
use yii\helpers\Json;
use yii\web\View;

Assets::register($this);
Assets::addLangFile($options['lang'], $this);

if(!empty($options['noConflict'])){
	Assets::noConflict($this);
}

unset($options['noConflict']);

$options['uiOptions'] = [
    // toolbar configuration
    'toolbar' => [
        ['back', 'forward'],
        ['getfile'],
        // ['reload'],
        // ['home', 'up'],
        ['mkdir', 'mkfile', 'upload'],
        [/*'open', */ 'download', 'info'],
        // ['quicklook'],
        ['copy', 'cut', 'paste'],
        ['rm'],
        [/*'duplicate',*/ 'rename', 'resize'/*, 'edit'*/],
        ['extract', 'archive'],
        ['search'],
        ['view'],
        ['help']
    ],
    // directories tree options
    'tree' => [
        // expand current root on init
        'openRootOnLoad' => true,
        // auto load current dir parents
        'syncTree' => true
    ]
];

$options['handlers'] = [

    'init' => new Yii\web\JsExpression('function(e, self) {
      var args = getArgs();

      if (args.folder_hash) {
        self.lastDir(args.folder_hash);
      }

      if (args.file_hash) {
        var selInt = setInterval(function() {

          //var els = jQuery("#" + (window.parent.selectedFiles[args.id]
          //? window.parent.selectedFiles[args.id] : args.file_hash));

          var els = jQuery("#" + args.file_hash);

          els.each(function(ind, el) {
              $el = jQuery(el);
              $(".elfinder-cwd-wrapper").scrollTo($el);
              $el.click();
              clearInterval(selInt);
          });
        }, 500);
      }
    }'),

    'add' => new Yii\web\JsExpression('function(e, self) {

      if (e.data.added) {
        var id = e.data.added[0].hash;
        if (id) {
          var selInt = setInterval(function() {
            $el = jQuery("#" + id);
            jQuery(".elfinder-cwd").scrollTo($el);
            clearInterval(selInt);
          }, 500);
        }
      }
    }')
];

$options['showFiles'] = 1000;
$options['useBrowserHistory'] = false;
$options['reloadClearHistory'] = false;

//if( Yii::$app->request->get('file_hash') ){
//    $options['rememberLastDir'] = false;
//} else {
    $options['rememberLastDir'] = true;
//}


$maxImageSize =  defined('MAX_IMAGE_SIZE_UPLOAD') ? constant('MAX_IMAGE_SIZE_UPLOAD') : 1 * 1024 * 1024 ;
$maxDocSize = defined('MAX_DOC_SIZE_UPLOAD') ? constant('MAX_DOC_SIZE_UPLOAD') : 15 * 1024 * 1024 ;



$this->registerJs('
//var maxRegularFileSizeAllowed = 15 * 1024 * 1024;
var maxFileSizeAllowed = 1 * 1024 * 1024;
var maxFileCountAllowed = 100;
var maxImageSize = ' .$maxImageSize. ';
var maxDocSize = ' .$maxDocSize . ';
', $this::POS_BEGIN);

$this->registerJs("

    function getArgs() {
        var args = new Object();
        var query = location.search.substring(1);
        var pairs = query.split('&');
        for(var i = 0; i < pairs.length; i++) {
            var pos = pairs[i].indexOf('=');
            if (pos == -1) continue;
            var argname   = pairs[i].substring(0,pos);
            var value     = pairs[i].substring(pos+1);
            args[argname] = unescape(value);
        }
        return args;
    }

    function ElFinderGetCommands(disabled){
        var Commands = elFinder.prototype._options.commands;
        $.each(disabled, function(i, cmd) {
            (idx = $.inArray(cmd, Commands)) !== -1 && Commands.splice(idx,1);
        });
        return Commands;
    }

    var winHashOld = '';
    function elFinderFullscreen(){

        var width = $(window).width()-($('#elfinder').outerWidth(true) - $('#elfinder').width());
        var height = $(window).height()-($('#elfinder').outerHeight(true) - $('#elfinder').height());
        var el = $('#elfinder').elfinder('instance');
        var winhash = $(window).width() + '|' + $(window).height();

        if(winHashOld == winhash)
            return;

        winHashOld = winhash;
        el.resize(width, height);
    }

    var elfInstance = $('#elfinder').elfinder(".Json::encode($options).").elfinder('instance');
    $(window).resize(elFinderFullscreen);
    elFinderFullscreen();
");

$this->registerJs("
        function getArgs() {
            var args = new Object();
            var query = location.search.substring(1);
            var pairs = query.split('&');
            for(var i = 0; i < pairs.length; i++) {
                var pos = pairs[i].indexOf('=');
                if (pos == -1) continue;
                var argname   = pairs[i].substring(0,pos);
                var value     = pairs[i].substring(pos+1);
                args[argname] = unescape(value);
            }
            return args;
        }
",View::POS_HEAD);
$this->registerCss("
html, body {
    height: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    position: relative;
    padding: 0; margin: 0;
}
");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>
    <?php $this->head() ?>
    <script type="text/javascript">

    </script>
</head>
<body>
<?php $this->beginBody() ?>
<div id="elfinder"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?= frontend\rating\widgets\CookieMessage\Message::widget(); ?>
<div id="sitename" class="section white">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="navbar-header hidden-xs"><span class="navbar-brand"></span></div>
                <h1><a href="/" title="">
                        Общероссийский рейтинг<br class="hidden-xs">образовательных сайтов
                    </a></h1>
            </div>
            <div class="col-sm-6 col-xs-12 hidden-xs">
                <div id="founders" class="pull-right" >
                    <div><a href="http://rosnou.ru/" target="_blank"
                            title="Учредитель рейтинга: Российский новый университет (РосНОУ)"><img
                                    src="<?=$this->img('logo-rosnou.png')?>" alt="РосНОУ"></a></div>
                    <div class="dotted-divider"></div>
                    <div><a href="http://www.prosv.ru/" target="_blank" title="Учредитель рейтинга: Группа компаний «Просвещение»">
                        <img id="logo-prosv" src="<?=$this->img('logo-prosv.png')?>" alt="Группа компаний Просвещение"></a>
                    </div>
                    <!--div>
                        Учредители<br>рейтинга
                    </div-->
                    <div class="dotted-divider"></div>
                    <div><a href="http://www.hse.ru/" target="_blank"
                            title="Методическая поддержка: Национальный исследовательский университет «Высшая школа экономики»"><img
                                    src="<?=$this->img('logo-hse.png')?>" alt="Высшая школа экономики"></a></div>
                    <!--div>
                        Методическая<br>поддержка
                    </div-->
                </div>
            </div>
        </div>
    </div>
</div>

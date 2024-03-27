
<? if (count($request_finished) > 0): ?>
<div class="col-sm-3 hidden-xs">
    <div class="panel panel-transparent">
        <div class="panel-body">
            <div class="thumbnail">
                <img alt="Участник рейтинга" class="img-circle thumb-large" src="/images/pennants/<?= $ScoreData['image'] ?>"/>
            </div>
        </div>
    </div>
        <h3>
            Дипломы
        </h3>
        <?php foreach ($request_finished as $Request): ?>            
            <a href="<?= yii\helpers\Url::home(true); ?>uchastniki/<?= $Site->id ?>/<?= $Request->period->slug ?>/getdiplom/" target="_blank"><?= $Request->period->title ?></a></br> 
        <?php endforeach ?>            
        <h3>Код кнопки</h3>               
        <div class="form-group">
            <textarea readonly="" class="form-control" rows="7">&lt;a href="<?= yii\helpers\Url::home(true); ?>uchastniki/<?= $Site->id ?>/"&gt;&lt;img src="<?= yii\helpers\Url::home(true); ?>images/pennants/<?= $ScoreData['image'] ?>" alt="Участник Общероссийского рейтинга образовательных сайтов"&gt;&lt;/a&gt;
            </textarea>
        </div>  
        <pre><?php // var_dump($ScoreData);?></pre> 
</div>
<? endif ?>

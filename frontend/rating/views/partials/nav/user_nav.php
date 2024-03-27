<?php if (Yii::$app->user->isGuest): ?>

    <div class="dropdown btn-user">
        <button type="button" id="authorized" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false"
                class="btn-blue btn-rounded"><i class="icon-rnuser"></i><span>ВХОД</span></button>
        <ul class="dropdown-menu">
            <li class="header-link">
                <a class="pull-right small"
                   href="<?=\yii\helpers\Url::toRoute('users/access/registration')?>"
                   title="">Регистрация</a><i
                    class="fa fa-caret-up fa-bluedark"></i>
            </li>
            <li class="dropdown-header uppercase">Войти в профайл</li>
            <li role="divider">
            <li>
                <form action="<?=\yii\helpers\Url::toRoute('users/access/login')?>" method="post"
                      class="dimox">
                    <div class="form-group"><input name="LoginForm[email]" type="email" class="form-control"
                                                   placeholder="Email"></div>
                    <div class="form-group"><input name="LoginForm[password]" type="password"
                                                   class="form-control"
                                                   placeholder="Пароль"></div>
                    <button type="submit" class="btn btn-blue btn-100">Войти</button>
                    <input style="display:none;" type="hidden" name="from_page"
                           value="/users/applications/">
                </form>
            </li>
        </ul>
    </div>

<?php else: ?>

    <div class="dropdown btn-user">
        <button type="button" id="authorized" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" class="btn-blue btn-rounded"><i
                class="icon-rnuser"></i><span>Профиль</span></button>
        <ul class="dropdown-menu" aria-labelledby="authorized">
            <li class="header-link">
                <a class="pull-right small" href="<?=\yii\helpers\Url::toRoute('users/access/logout')?>"
                   title="">Выйти</a><i class="fa fa-caret-up fa-bluedark"></i>
            </li>
            <li role="member">
                            <span class="member-box"><span class="thumbnail"><img
                                        src="<?=Yii::$app->user->getIdentity()->getAvatar($this)?>" alt="Нет фотографии"
                                        class="img-circle thumb-small"></span><span class="member-info">
                                    <span class="member">
                                        <?=Yii::$app->user->getIdentity()->firstname?>
                                        <?=Yii::$app->user->getIdentity()->lastname?>
                                    </span>
                                    <span class="member-duty"><?=Yii::$app->user->getIdentity()->orgname?></span>
                                </span>
                            </span>
            </li>
            <li><a href="<?=\yii\helpers\Url::toRoute('users/profile/index')?>">Мой профиль</a></li>

            <?php if(Yii::$app->user->can('user')):?>
                <li role="divider">
                <li>
                    <a href="<?=\yii\helpers\Url::toRoute('sites/sites/index')?>">Мои сайты</a>
                </li>
            <?php endif?>

            <?php if(Yii::$app->user->can('moderator')):?>
                <li role="divider">
                <li>
                    <a href="<?=\yii\helpers\Url::toRoute('sites/sites/moderate-list')?>">Заявки участников</a>
                </li>
            <?php endif?>

            <?php if(Yii::$app->user->can('expert')):?>
                <li role="divider">
                <li>
                    <a href="<?=\yii\helpers\Url::toRoute('users/examination/expert-list')?>">Заявки участников</a>
                </li>
            <?php endif?>
        </ul>
    </div>

<?php endif ?>
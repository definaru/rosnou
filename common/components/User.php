<?php

namespace common\components;

use yii\rbac\Role;

class User extends \yii\web\User
{
    public function init()
    {
        parent::init();

        $this->assignRoles();
    }

    public function assignRoles()
    {
        /** @var \common\models\User $model */
        $model = $this->getIdentity();

        if(!$model) {
            return;
        }

        $authManager = \Yii::$app->authManager;

        if($model->is_expert) {
            $authManager->assign(new Role(['name' => 'expert']), $this->getId());
        }

        if($model->is_moderator) {
            $authManager->assign(new Role(['name' => 'moderator']), $this->getId());
        }

        if($model->is_admin) {
            $authManager->assign(new Role(['name' => 'admin']), $this->getId());
        }

        if(!$model->is_expert AND !$model->is_moderator AND !$model->is_admin) {
            if(!$model->isActivated()) {
                $authManager->assign(new Role(['name' => 'not-activated-user']), $this->getId());
            } else {
                $authManager->assign(new Role(['name' => 'user']), $this->getId());
            }
        }
    }
}
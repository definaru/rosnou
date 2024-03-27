<?php

namespace frontend\rating\components\filters;

class AccessRule extends \yii\filters\AccessRule
{
    public $mustBeActivated = true;

    public function allows($action, $user, $request)
    {
        $result = $this->matchAction($action)
            && $this->matchRole($user)
            && $this->matchIP($request->getUserIP())
            && $this->matchVerb($request->getMethod())
            && $this->matchController($action->controller)
            && $this->matchCustom($action);

        if($user->isGuest OR $user->getIdentity()->isActivated() OR !$this->mustBeActivated OR in_array('?', $this->roles)) {
            if($result) {
                return $this->allow;
            } else {
                return null;
            }
        }

        if(!$user->getIdentity()->isActivated()) {
            return \Yii::$app->response->redirect(['users/access/not-activated']);
        }

        return $this->allow;
    }
}
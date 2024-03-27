<?php

namespace common\models;

class VerificationToken extends \yii\db\ActiveRecord
{
    const TYPE_EMAIL_VERIFICATION = 1;
    const TYPE_PASSWORD_RECOVERY = 2;

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
        return '{{%verification_token}}';
    }
}
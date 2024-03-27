<?php

namespace common\models;

use common\user\Name;
use domain\ModelSaveException;
use frontend\rating\components\View;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const USER_ADMIN = 1;
    const USER_EXPERT =2;
    const USER_MODERATOR = 3;

    public static $roles = [
        self::USER_ADMIN => 'Супервизор',
        self::USER_EXPERT => 'Эксперт',
        self::USER_MODERATOR => 'Модератор',
    ];

    public static function getRoles() {
        return self::$roles;
    }

    /**
     * @return User
     */
    public function isActivated()
    {
        return $this->email_verified;
    }

    /**
     * @return $this
     */
    public function emailVerified()
    {
        $this->email_verified = true;

        return $this;
    }

    /**
     * @param Name $name
     * @param string $login
     * @param string $email
     * @param string $password
     * @param string $orgName
     * @param string $orgPosition
     * @return static
     */
    public static function register(Name $name, string $email, string $passwordHash, string $orgName, string $orgPosition)
    {
        $user = new static();
        $user->email = $email;
        $user->firstname = $name->first();
        $user->lastname = $name->last();
        $user->middlename = $name->middle();
        $user->password_hash = $passwordHash;
        $user->orgname = $orgName;
        $user->position = $orgPosition;

        if(!$user->save()) {
            throw new ModelSaveException($user);
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],

            ['password_hash', 'required'],

            ['lastname', 'required'],
            ['lastname', 'string', 'length' => [1, 255]],

            ['firstname', 'required'],
            ['lastname', 'string', 'length' => [1, 255]],

            ['middlename', 'required'],
            ['lastname', 'string', 'length' => [1, 255]],

            ['orgname', 'required'],

            ['position', 'required'],

            [['lastname', 'firstname', 'middlename'], 'string', 'length' => [1, 255]],

            [['is_admin', 'is_expert', 'is_moderator'], 'boolean'],

            ['login', 'string'],
            ['login', 'unique'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'firstname' => 'Имя',
            'middlename' => 'Отчество',
            'lastname' => 'Фамилия',
            'orgname' => 'Название организации',
            'position' => 'Должность',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'created_timestamp' => 'Created Timestamp',
            'email_verified' => 'Подтв. почты',
            'auth_key' => 'Auth Key',
            'is_moderator' => 'Модератор',
            'is_admin' => 'Супервизор',
            'is_expert' => 'Эксперт',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
        ]);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @param $email
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param View|null $view
     * @return bool|string
     */
    public function getAvatar(View $view)
    {
        if($this->avatar_image) {
            return '/images/avatars/' . $this->avatar_image;
        }

        return $view->img('no-photo.png');
    }

    /**
     * @param $path
     * @return $this
     */
    public function setAvatar($path)
    {
        $this->avatar_image = $path ? $path . '?' . time() : null;

        return $this;
    }

    public function getFio(){

        $list[] = $this->lastname;
        $list[] = $this->middlename;
        $list[] = $this->firstname;

        return join($list, ' ');
    }


    /**
     * Извлекаем имя пользователя, в зависимости от имеющихся данных
     */
    public function getName( $format = 1 ){

        $nameList = [];

        $order[1] = ['lastname', 'firstname', 'middlename'];
        $order[2] = ['firstname', 'middlename', 'lastname'];

        foreach($order[$format] as $field){
            if( !empty($this->$field) ){
                $nameList[] = $this->$field;
            }
        }

        return sizeof($nameList) ? join($nameList, ' ') : $this->email;
    }
}
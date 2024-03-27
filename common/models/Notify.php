<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notify}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property string $finish_datetime
 * @property integer $active_flag
 * @property integer $sendemail_flag
 */
class Notify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notify}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['title', 'body'], 'string'],
            [['finish_datetime'], 'safe'],
            [['active_flag', 'sendemail_flag', 'sendlock_flag', 'sendfinish_flag'], 'integer']
        ];
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'body' => 'Текст',
            'finish_datetime' => 'Время снятия с публикации',
            'active_flag' => 'Активность',
            'sendemail_flag' => 'Разослать на email',
            'sendlock_flag' => 'Рассылка в процессе',
            'sendfinish_flag' => 'Cообщение разослано',
        ];
    }
}

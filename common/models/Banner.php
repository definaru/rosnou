<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $image_file
 * @property integer $type_index
 * @property integer $list_order
 */
class Banner extends \yii\db\ActiveRecord
{

    const BANNER_MAIN_PAGE = 1;
    const BANNER_INFO_PARTNER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'image_file', 'type_index', 'url'], 'required'],
            [['title','url'], 'string'],
            [['type_index', 'list_order'], 'integer'],
            [['image_file'], 'string'],
            [['list_order'], 'default', 'value'=> 0],
        ];
    }

    
    
    function getTypeIndexList($index = null){
        $list = [
          self::BANNER_MAIN_PAGE => "Баннеры на главной",
          self::BANNER_INFO_PARTNER => "Информационные партнеры",
        ];

        if( $index !== null ){
          return array_key_exists($index, $list) ? $list[$index] : '';
        }
        return $list;
    }
        
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'image_file' => 'Изображение',
            'type_index' => 'Тип баннера',
            'list_order' => 'Порядок сортировки',
            'url' => 'Ссылка',
        ];
    }
}

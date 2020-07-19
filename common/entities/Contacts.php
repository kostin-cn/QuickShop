<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property int $id
 * @property int $restaurant_id
 * @property string $type
 * @property string $value
 * @property string $value_ru
 * @property string $value_en
 * @property int $sort
 * @property int $status
 *
 * @property Restaurants $restaurant
 */
class Contacts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    const VARIANTS = [
        'phone' => 'Телефон',
        'envelope' => 'Почта',
        'point' => 'Адрес',
        'clock' => 'Время работы',
        'other' => 'Другое',
    ];

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
//                'scope' => function () {
//                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['value_ru'], 'required'],
            [['value_ru', 'value_en'], 'string'],
            [['status', 'status'], 'integer'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип данных',
            'value_ru' => 'Значение',
            'value_en' => 'Значение EN',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurants::className(), ['id' => 'restaurant_id']);
    }

    public function getValue()
    {
        return $this->getAttr('value');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }
}

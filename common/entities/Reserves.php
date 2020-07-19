<?php

namespace common\entities;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%reserves}}".
 *
 * @property string $id
 * @property string $restaurant_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property integer $date
 * @property string $time
 * @property string $notes
 * @property string $language
 * @property integer $qty
 * @property integer $dispatch
 * @property integer $status
 *
 * @property Restaurants $restaurant
 */
class Reserves extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%reserves}}';
    }

    public function rules()
    {
        return [
            [['name', 'date', 'qty', 'email', 'phone', 'restaurant_id'], 'required'],
            [['qty', 'dispatch', 'status', 'restaurant_id'], 'integer'],
            [['email'], 'email'],
            [['name'], 'string', 'max' => 255],
            [['date'], 'integer'],
            [['time',], 'string', 'max' => 11],
            [['phone'], 'string', 'max' => 20],
            [['language'], 'string', 'max' => 2],
            [['notes'], 'string'],
            [['dispatch'], 'default', 'value' => '0'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Имя',
            'email' => 'Е-мейл',
            'phone' => 'Телефон',
            'date' => 'Дата',
            'time' => 'Время',
            'qty' => 'Персон',
            'dispatch' => 'Рассылка',
            'status' => 'Статус',
            'notes' => 'Пожелания',
            'language' => 'Язык',
        ];
    }

    public function getRestaurant()
    {
        return $this->hasOne(Restaurants::class, ['id' => 'restaurant_id']);
    }
}

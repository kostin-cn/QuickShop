<?php

namespace common\entities;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $quantity
 * @property int $persons
 * @property int $cost
 * @property int $change_from
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $phone
 * @property string $datetime
 * @property string $when_ready
 * @property string $pay_method
 * @property string $note
 * @property string $cart_json
 * @property int $status
 * @property int $user_status
 *
 * @property OrderItems[] $orderItems
 * @property User $user
 */
class Orders extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%orders}}';
    }

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'address'], 'required'],
            [['note', 'datetime', 'cart_json'], 'string'],
            [['name', 'phone', 'pay_method', 'when_ready'], 'string', 'max' => 20],
            [['email', 'address'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['cart_json'], 'safe'],
            [['quantity', 'cost', 'change_from', 'persons'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quantity' => 'Количество товаров',
            'persons' => 'Количество персон',
            'cost' => 'Сумма',
            'change_from' => 'Сдача с',
            'created_at' => 'Оформлен',
            'updated_at' => 'Обработан',
            'datetime' => 'Время доставки',
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'note' => 'Комментарии',
            'status' => 'Статус',
            'user_status' => 'Доступно к удалению',
            'pay_method' => 'Способ оплаты',
            'when_ready' => 'По готовности',
        ];
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}

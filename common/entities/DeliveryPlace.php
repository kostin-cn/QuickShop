<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use common\entities\Cities;

/**
 * This is the model class for table "{{%delivery_place}}".
 *
 * @property int $id
 * @property int $cities_id
 * @property double $lat
 * @property double $long
 * @property int $sort
 * @property int $status
 *
 * @property Cities $city
 */
class DeliveryPlace extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%delivery_place}}';
    }

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
            [['cities_id', 'lat', 'long'], 'required'],
            [['cities_id', 'sort', 'status'], 'integer'],
            [['lat', 'long'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cities_id' => 'Город',
            'lat' => 'Широта',
            'long' => 'Долгота',
            'sort' => 'Порядок',
            'status' => 'Статус',
        ];
    }

    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'cities_id']);
    }
}

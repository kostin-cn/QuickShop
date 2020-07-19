<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%events_attachments}}".
 *
 * @property int $id
 * @property int $event_id
 * @property string $path
 * @property string $base_url
 * @property string $type
 * @property int $size
 * @property string $name
 * @property int $order
 *
 * @property Events $event
 */
class Events_attachments extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%events_attachments}}';
    }

    public function rules()
    {
        return [
            [['event_id'], 'required'],
            [['event_id', 'size', 'order'], 'integer'],
            [['path', 'base_url', 'type', 'name'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'path' => 'Path',
            'base_url' => 'Base Url',
            'type' => 'Type',
            'size' => 'Size',
            'name' => 'Name',
            'order' => 'Order',
        ];
    }

    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    public function getUrl()
    {
        return $this->base_url . '/' . $this->path;
    }
}

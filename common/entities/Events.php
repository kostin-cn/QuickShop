<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use trntv\filekit\behaviors\UploadBehavior;
use yii\behaviors\SluggableBehavior;
use common\entities\Restaurants;

/**
 * This is the model class for table "{{%events}}".
 *
 * @property int $id
 * @property int $variants
 * @property string $title_ru
 * @property string $title_en
 * @property string $slug
 * @property string $short_descr_ru
 * @property string $short_descr_en
 * @property string $description_ru
 * @property string $description_en
 * @property string $image_name
 * @property int $gallery
 * @property string $date
 * @property int $status
 * @property string $meta_keywords_ru
 * @property string $meta_keywords_en
 * @property string $meta_description_ru
 * @property string $meta_description_en
 *
 * @property Events_Attachments[] $eventsAttachments
 * @property Restaurants $restaurant
 */
class Events extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%events}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'attachments',
                'multiple' => true,
                'uploadRelation' => 'eventsAttachments',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'orderAttribute' => 'order',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title_ru',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['restaurants_id', 'title_ru', 'short_descr_ru', 'description_ru', 'image_name', 'variants'], 'required'],
            [['short_descr_ru', 'short_descr_en', 'description_ru', 'description_en', 'meta_keywords_ru', 'meta_keywords_en', 'meta_description_ru', 'meta_description_en'], 'string'],
            [['restaurants_id', 'gallery', 'status', 'variants'], 'integer'],
            [['title_ru', 'title_en', 'slug', 'image_name'], 'string', 'max' => 50],
            [['date'], 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'date', 'on' => ['create', 'update']],
            [['slug'], 'unique'],
            [['attachments'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'variants' => 'Событие',
            'restaurants_id' => 'Ресторан',
            'title_ru' => 'Заголовок',
            'title_en' => 'Заголовок En',
            'slug' => 'Slug',
            'short_descr_ru' => 'Краткое описание',
            'short_descr_en' => 'Краткое описание En',
            'description_ru' => 'Описание',
            'description_en' => 'Описание En',
            'image_name' => 'Изображение',
            'image_file' => 'Изображение',
            'gallery' => 'Галерея',
            'date' => 'Дата',
            'status' => 'Статус',
            'attachments' => 'Галерея',
            'meta_keywords_ru' => 'Meta Keywords',
            'meta_keywords_en' => 'Meta Keywords EN',
            'meta_description_ru' => 'Meta Description',
            'meta_description_en' => 'Meta Description En',
        ];
    }

    public $attachments;
    const VARIANTS = [
        0 => 'Концерты',
        1 => 'Акции',
        2 => 'Детям',
        3 => 'Новости',
    ];

    public function getRestaurant()
    {
        return $this->hasOne(Restaurants::className(), ['id' => 'restaurants_id']);
    }

    public function getEventsAttachments()
    {
        return $this->hasMany(Events_attachments::className(), ['event_id' => 'id']);
    }


    public function getTitle()
    {
        return $this->getAttr('title');
    }

    public function getShortDescr()
    {
        return $this->getAttr('short_descr');
    }

    public function getDescription()
    {
        return $this->getAttr('description');
    }

    public function getMetaKeywords()
    {
        return $this->getAttr('meta_keywords');
    }

    public function getMetaDescription()
    {
        return $this->getAttr('meta_description');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

    #################### IMAGES ####################

    private $imageWidth = 1920;
    private $imageHeight = null;
    private $quality = 85;

    public function __construct(array $config = [])
    {
        $folderName = str_replace(['{', '}', '%'], '', $this::tableName());
        parent::__construct($config);
        $this->_folder = '/files/' . $folderName . '/';
        $this->_folderPath = Yii::getAlias('@files') . '/' . $folderName . '/';
    }

    public $uploadedImageFile;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            if ($this->uploadedImageFile) {
                if (!$this->isNewRecord) {
                    $this->deleteImage();
                }
                if (!$this->image_name) {
                    /* @var $lastModel self */
                    $lastModel = self::find()->orderBy(['id' => SORT_DESC])->one();
                    $id = $lastModel->id + 1;
                } else {
                    $id = $this->id;
                }
                $this->image_name = $id . '.' . $this->uploadedImageFile->extension;
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->uploadedImageFile) {
            $path = $this->_folderPath . $this->image_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedImageFile->saveAs($path);
            if ($this->uploadedImageFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => $this->quality]);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }

}

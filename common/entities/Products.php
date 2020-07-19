<?php

namespace common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\SluggableBehavior;
use backend\components\SortableBehavior;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property int $price
 * @property string $weight
 * @property string $description
 * @property string $image_name
 * @property int $status
 * @property int $category_status
 * @property int $sort
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ProductCategories $category
 * @property UploadedFile $uploadedImageFile
 * @property string $image
 */
class Products extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%products}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::class,
                'scope' => function () {
                    return Products::find()->where(['category_id' => $this->category_id]);
                }
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => null,
            ]
        ];
    }

    public function rules()
    {
        return [
            [['category_id', 'title', 'description', 'image_name'], 'required'],
            [['category_id', 'price'], 'integer'],
            [['description'], 'string'],
            [['title', 'slug', 'image_name', 'weight'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['uploadedImageFile'], 'safe'],
            [['uploadedImageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            ['uploadedImageFile', 'required', 'when' => function () {
                return !$this->image_name;
            }, 'whenClient' => true],


        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'image_name' => 'Изображение',
            'uploadedImageFile' => 'Изображение',
            'price' => 'Цена',
            'description' => 'Описание',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'weight' => 'Вес',
        ];
    }

    public function getProductWeight()
    {
        $arr = explode('/', trim(str_replace(['г', 'g'], '', $this->weight)));
        $result = 0;
        foreach ($arr as $item) {
            $result += is_numeric(trim($item)) ? $item : 0;
        }
        return $result;
    }

    public function getCategory()
    {
        return $this->hasOne(ProductCategories::class, ['id' => 'category_id']);
    }

    #################### IMAGES ####################

    private $imageWidth = 1000;
    private $imageHeight = 1180;
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

<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%product_categories}}".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $image_name
 * @property int $sort
 * @property int $status
 * @property int $show_on_home
 * @property int $width
 * @property int $height
 * @property int $grid
 *
 * @property Products[] $products
 */
class ProductCategories extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%product_categories}}';
    }

    public function behaviors( ) {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ],
            [
                'class' => SortableBehavior::class,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'status', 'show_on_home', 'width', 'height'], 'integer'],
            [['grid'], 'safe'],
            [['title', 'slug', 'image_name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Slug',
            'image_name' => 'Изображение',
            'image_file' => 'Изображение',
            'sort' => 'Порядок',
            'status' => 'Статус',
            'show_on_home' => 'Показывать на главной',
            'width' => 'Ширина',
            'height' => 'Высота',
            'grid' => 'Размер плитки',
        ];
    }

    public $grid;
    private $gridWidthHeight = [
        '11' => '1x1',
        '21' => '1.5x1',
        '12' => '1x1.5',
        '22' => '1.5x1.5',
        '31' => '2x1',
        '32' => '2x1.5',
        '13' => '1x2',
        '23' => '1.5x2',
        '33' => '2x2',
    ];

    public function setGrid()
    {
        $this->grid = $this->width . $this->height;
    }

    public function saveGrid()
    {
        $this->width = $this->grid[0];
        $this->height = $this->grid[1];
    }

    public function getGrid()
    {
        return $this->gridWidthHeight;
    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['category_id' => 'id']);
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

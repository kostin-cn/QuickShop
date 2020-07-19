<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yii\behaviors\SluggableBehavior;
use common\entities\Cities;
use common\entities\Reserves;

/**
 * This is the model class for table "{{%restaurants}}".
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $slug
 * @property string $short_descr_ru
 * @property string $short_descr_en
 * @property string $description_ru
 * @property string $description_en
 * @property string $banquets_ru
 * @property string $banquets_en
 * @property string $image_name
 * @property string $logo_name
 * @property string $icon_name
 * @property string $link
 * @property string $address_ru
 * @property string $address_en
 * @property string $phone
 * @property double $lat
 * @property double $long
 * @property string $delivery_place
 * @property int $status
 * @property string $meta_keywords_ru
 * @property string $meta_keywords_en
 * @property string $meta_description_ru
 * @property string $meta_description_en
 *
 * @property Cities $city
 */
class Restaurants extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%restaurants}}';
    }

    public function behaviors()
    {
        return [
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
            [['cities_id', 'title_ru', 'short_descr_ru', 'description_ru', 'image_name'], 'required'],
            [['short_descr_ru', 'short_descr_en', 'description_ru', 'description_en', 'banquets_ru', 'banquets_en', 'link', 'meta_keywords_ru', 'meta_keywords_en', 'meta_description_ru', 'meta_description_en'], 'string'],
            [['cities_id', 'status'], 'integer'],
            [['lat', 'long'], 'number'],
            [['delivery_place'], 'string'],
            [['title_ru', 'title_en', 'slug', 'image_name', 'logo_name', 'icon_name'], 'string', 'max' => 50],
            [['address_ru', 'address_en', 'phone'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cities_id' => 'Город',
            'title_ru' => 'Заголовок',
            'title_en' => 'Заголовок En',
            'slug' => 'Slug',
            'short_descr_ru' => 'Краткое описание',
            'short_descr_en' => 'Краткое описание En',
            'description_ru' => 'Описание',
            'description_en' => 'Описание En',
            'banquets_ru' => 'Банкеты',
            'banquets_en' => 'Банкеты EN',
            'image_name' => 'Изображение',
            'image_file' => 'Изображение',
            'logo_name' => 'Логотип',
            'icon_name' => 'Иконка для карты',
            'link' => 'Ссылка',
            'address_ru' => 'Адрес',
            'address_en' => 'Адрес EN',
            'phone' => 'Телефон',
            'lat' => 'Широта',
            'long' => 'Долгота',
            'delivery_place' => 'Зона доставки',
            'status' => 'Статус',
            'meta_keywords_ru' => 'Meta Keywords',
            'meta_keywords_en' => 'Meta Keywords EN',
            'meta_description_ru' => 'Meta Description',
            'meta_description_en' => 'Meta Description En',
        ];
    }

    public function getReserves()
    {
        return $this->hasMany(Reserves::class, ['restaurant_id' => 'id']);
    }

    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'cities_id']);
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

    public function getBanquets()
    {
        return $this->getAttr('banquets');
    }

    public function getAddress()
    {
        return $this->getAttr('address');
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
    public $uploadedLogoFile;
    public $uploadedIconFile;
    private $_folder;
    private $_folderPath;

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->uploadedImageFile = UploadedFile::getInstance($this, 'uploadedImageFile');
            $this->uploadedLogoFile = UploadedFile::getInstance($this, 'uploadedLogoFile');
            $this->uploadedIconFile = UploadedFile::getInstance($this, 'uploadedIconFile');
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
            if ($this->uploadedLogoFile) {
                if (!$this->isNewRecord) {
                    $this->deleteLogo();
                }
                if (!$this->logo_name) {
                    /* @var $lastModel self */
//                    $lastModel = self::find()->orderBy(['id' => SORT_DESC])->one();
//                    $id = $lastModel->id + 1;
                    $id = date('YmdHis');
                } else {
                    $id = $this->id;
                }
                $this->logo_name = $id . '_logo' . '.' . $this->uploadedLogoFile->extension;
            }
            if ($this->uploadedIconFile) {
                if (!$this->isNewRecord) {
                    $this->deleteIcon();
                }
                if (!$this->icon_name) {
                    /* @var $lastModel self */
//                    $lastModel = self::find()->orderBy(['id' => SORT_DESC])->one();
//                    $id = $lastModel->id + 1;
                    $id = date('YmdHis');
                } else {
                    $id = $this->id;
                }
                $this->icon_name = $id . '_icon' . '.' . $this->uploadedIconFile->extension;
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
        if ($this->uploadedLogoFile) {
            $path = $this->_folderPath . $this->logo_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedLogoFile->saveAs($path);
            if ($this->uploadedLogoFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => $this->quality]);
            }
        }
        if ($this->uploadedIconFile) {
            $path = $this->_folderPath . $this->icon_name;
            FileHelper::createDirectory(dirname($path, 1));
            $this->uploadedIconFile->saveAs($path);
            if ($this->uploadedIconFile->extension != 'svg') {
                Image::thumbnail($path, $this->imageWidth, $this->imageHeight)->save($path, ['quality' => $this->quality]);
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteImage();
        $this->deleteLogo();
        $this->deleteIcon();
    }

    public function deleteImage()
    {
        if ($this->image_name) {
            if (file_exists($this->_folderPath . $this->image_name)) {
                unlink($this->_folderPath . $this->image_name);
            }
        }
    }

    public function deleteLogo()
    {
        if ($this->logo_name) {
            if (file_exists($this->_folderPath . $this->logo_name)) {
                unlink($this->_folderPath . $this->logo_name);
            }
        }
    }

    public function deleteIcon()
    {
        if ($this->icon_name) {
            if (file_exists($this->_folderPath . $this->icon_name)) {
                unlink($this->_folderPath . $this->icon_name);
            }
        }
    }

    public function removeImage()
    {
        $this->deleteImage();
        $this->image_name = null;
        $this->save();
    }

    public function removeLogo()
    {
        $this->deleteLogo();
        $this->logo_name = null;
        $this->save();
    }

    public function removeIcon()
    {
        $this->deleteIcon();
        $this->icon_name = null;
        $this->save();
    }

    public function getImage()
    {
        return $this->_folder . $this->image_name;
    }

    public function getLogo()
    {
        return $this->_folder . $this->logo_name;
    }

    public function getIcon()
    {
        return $this->_folder . $this->icon_name;
    }
}

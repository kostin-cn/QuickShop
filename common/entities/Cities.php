<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;
use backend\components\SortableBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%cities}}".
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $slug
 * @property string $url
 * @property string $feedback_email
 * @property int $sort
 * @property int $status
 * @property int $default
 */
class Cities extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cities}}';
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
            [['title_ru', 'url'], 'required'],
            [['status', 'default'], 'integer'],
            [['title_ru', 'title_en', 'slug', 'feedback_email'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 50],
            [['slug', 'url'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title_ru' => 'Заголовок',
            'title_en' => 'Заголовок En',
            'slug' => 'Slug',
            'url' => 'Url',
            'feedback_email' => 'E-mail для заказов',
            'status' => 'Статус',
            'default' => 'По-умолчанию',
            'sort' => 'Порядок',
        ];
    }

    public function getTitle()
    {
        return $this->getAttr('title');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }

//Переменная, для хранения текущего объекта города
    static $current = null;

//Получение текущего объекта города
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultCity();
        }
        return self::$current;
    }

//Установка текущего объекта города и локаль пользователя
    static function setCurrent($url = null)
    {
        $city = self::getCityByUrl($url);
        self::$current = ($city === null) ? self::getDefaultCity() : $city;
        Yii::$app->params['city'] = self::$current->url;
    }

//Получения объекта города по умолчанию
    static function getDefaultCity()
    {
        return Cities::find()->where(['default' => 1])->one();
    }

//Получения объекта города по буквенному идентификатору
    static function getCityByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $city = Cities::find()->where(['url' => $url])->one();
            if ( $city === null ) {
                return null;
            }else{
                return $city;
            }
        }
    }

//Получаем массив координат для отрисовки зоны доставки
    public function getDeliveryPlace($id)
    {
        return DeliveryPlace::find()->where(['cities_id' => $id])->andWhere(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();
    }
}

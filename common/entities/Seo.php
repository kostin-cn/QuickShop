<?php

namespace common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property int $id
 * @property string $page
 * @property string $meta_title
 * @property string $meta_title_ru
 * @property string $meta_title_en
 * @property string $meta_description
 * @property string $meta_description_ru
 * @property string $meta_description_en
 * @property string $meta_keywords
 * @property string $meta_keywords_ru
 * @property string $meta_keywords_en
 */
class Seo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public function rules()
    {
        return [
            [['page'], 'required'],
            [['page'], 'string', 'max' => 50],
            [['meta_title_ru', 'meta_title_en', 'meta_description_ru', 'meta_description_en', 'meta_keywords_ru', 'meta_keywords_en'], 'string', 'max' => 255],
            [['page'], 'unique', 'targetAttribute' => ['page']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
            'meta_title_ru' => 'Meta Title Ru',
            'meta_title_en' => 'Meta Title En',
            'meta_description_ru' => 'Meta Description Ru',
            'meta_description_en' => 'Meta Description En',
            'meta_keywords_ru' => 'Meta Keywords Ru',
            'meta_keywords_en' => 'Meta Keywords En',
        ];
    }

    public function getMeta_title()
    {
        return $this->getAttr('meta_title');
    }

    public function getMeta_description()
    {
        return $this->getAttr('meta_description');
    }

    public function getMeta_keywords()
    {
        return $this->getAttr('meta_keywords');
    }

    private function getAttr($attribute)
    {
        $attr = $attribute . '_' . Yii::$app->language;
        $def_attr = $attribute . '_' . Yii::$app->params['defaultLanguage'];
        return $this->$attr ?: $this->$def_attr;
    }
}

<?php
namespace frontend\components;

//use yii\web\UrlManager;
use codemix\localeurls\UrlManager;
use common\entities\Cities;

class CityUrlManager extends UrlManager
{
    public function createUrl($params)
    {
        if( isset($params['city_id']) ){
            //Если указан идентефикатор города, то делаем попытку найти язык в БД,
            //иначе работаем с городом по умолчанию
            $city = Cities::findOne($params['city_id']);
            if( $city === null ){
                $city = Cities::getDefaultCity();
            }

            unset($params['city_id']);
        } else {
            //Если не указан параметр города, то работаем с текущим языком
            $city = Cities::getCurrent();
        }

        $url = parent::createUrl($params);

        return $url == '/' ? '/'.$city->url : '/'.$city->url.$url;
    }
}
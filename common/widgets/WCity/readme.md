Необходимые Компоненты:

1. Таблица "cities" в БД с полями:
    'id' -> int 11
    'title_ru' -> varchar 255
    'title_en' -> varchar 255
    'slug' -> varchar 255
    'url' -> varchar 50
    'feedback_email' -> varchar 255
    'sort' -> int 11
    'status' -> tinyint 1
    'default' -> tinyint 1

2. model Cities.php common\entities

3. controller CitiesController.php backend\controllers

4. views backend\views\cities

5. widget WCity.php common\widgets\WCity
    view.php common\widgets\WCity\views

6. urlmanager frontend\components\CityUrlManager.php

7. request frontend\components\CityRequest.php

8. записи в файле frontend\config\main.php
        'request' => [
            'class' => 'frontend\components\CityRequest'
        ],
        'urlManager' => [
            'class'=>'frontend\components\CityUrlManager',
        ],

9. отображение на сайте
    use common\widgets\WCity\WCity;
    <?= WCity::widget(); ?>

10. Url текущего города находится в Yii::$app->params['city']

Пример работы виджета http://merzen.otlr.net/
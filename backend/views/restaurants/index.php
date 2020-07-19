<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Restaurants;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рестораны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'cities_id',
                        'format' => 'raw',
                        'value' => function (Restaurants $data) {
                            return $data->city->title_ru;
                        },
                        'options' => ['style' => 'width: 100px; max-width: 100px;'],
                    ],
                    [
                        'attribute' => 'title_ru',
                        'contentOptions' => ['style' => 'white-space: normal;']
                    ],
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function (Restaurants $data) {
                            if ($data->image_name) {
                                return Html::img($data->image, [
                                    'alt' => 'yii2 - картинка в gridview',
                                    'style' => 'width:200px;'
                                ]);
                            }
                            return null;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function (Restaurants $data) {
                            if ($data->status) {
                                return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                            }
                        },
                        'options' => ['style' => 'width: 100px; max-width: 100px;'],
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>

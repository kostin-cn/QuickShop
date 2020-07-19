<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Events;
use kartik\date\DatePicker;
use common\entities\Restaurants;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События';
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
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'date',
                        'format' => 'raw',
                        'value' => function (Events $data) {
                            return Yii::$app->formatter->asDate($data->date, 'dd.MM.yyyy');
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ]
                        ]),
                        'options' => ['width' => '200'],
                    ],
                    [
                        'label' => 'Город',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->restaurant->city->title_ru;
                        }
                    ],
                    [
                        'attribute' => 'restaurants_id',
                        'format' => 'raw',
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'restaurants_id',
                            ArrayHelper::map(Restaurants::find()->all(), 'id', 'title_ru', function ($item){return $item->city->title_ru;}),
                            ['class' => 'form-control', 'prompt' => 'Все']
                        ),
                        'value' => function ($data) {
                            return $data->restaurant->title_ru;
                        }
                    ],
                    [
                        'attribute' => 'variants',
                        'value' => function ($data) {
                            return $data::VARIANTS[$data->variants];
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'variants',
                            Events::VARIANTS,
                            ['class' => 'form-control', 'prompt' => 'Все']
                        ),
                        'options' => ['style' => 'width: 150px; max-width: 150px;'],
                    ],
                    [
                        'attribute' => 'title_ru',
                        'contentOptions' => ['style' => 'white-space: normal;']
                    ],
                    [
                        'label' => 'Изображение',
                        'format' => 'raw',
                        'value' => function (Events $data) {
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
                        'value' => function (Events $data) {
                            if ($data->status) {
                                return Html::a('<span class="glyphicon glyphicon-ok"></span> Отображать', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                            }
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            ['0' => 'Скрывать', '1' => 'Отображать'],
                            ['class' => 'form-control', 'prompt' => 'Все']
                        ),
                        'options' => ['style' => 'width: 100px; max-width: 100px;'],
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>

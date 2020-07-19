<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Reserves;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\ReservesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $restaurant \common\entities\Restaurants */

$this->title = $restaurant->title_ru;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <p>
        <?= Html::a('Добавить', ['create', 'restaurant_id' => $restaurant->id], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    'email:email',
                    'phone',
                    [
                        'attribute' => 'date',
                        'format' => 'raw',
                        'value' => function ($data) {
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
                    'time',
                    'qty',
//                    'dispatch',
//                    'language',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->status) {
                                return Html::a('<span class="glyphicon glyphicon-ok"></span> Подтвержден', ['status', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', ['status', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
                            }
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel,
                            'status',
                            ['0' => 'Ожидает', '1' => 'Подтвержен'],
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

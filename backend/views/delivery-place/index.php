<?php

use arogachev\sortable\grid\SortableColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\DeliveryPlace;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зона доставки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-index">

    <p>
        <?= Html::a('Добавить', ['create', 'city_id' => $city_id], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'sort',
                    ],
                    [
                        'class' => SortableColumn::class,
                        'gridContainerId' => 'question-sortable',
                        'baseUrl' => '/admin/sort/', // Optional, defaults to '/sort/'
                        'confirmMove' => false, // Optional, defaults to true
                        'template' => '<div class="sortable-section">{currentPosition}</div>
                                           <div class="sortable-section">{moveWithDragAndDrop}</div>'
                    ],
                    'lat',
                    'long',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($data) {
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

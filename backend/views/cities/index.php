<?php

use arogachev\sortable\grid\SortableColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Cities;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">
        
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div class="question-index" id="question-sortable">
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
                    'title_ru',
                    'slug',
                    'url',
                    'feedback_email',
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
                    [
                        'attribute' => 'default',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->default) {
                                return Html::a('<span class="glyphicon glyphicon-ok"></span> Default', ['default', 'id' => $data->id], ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Non-Default', ['default', 'id' => $data->id], ['class' => 'btn btn-danger btn-raised']);
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
</div>

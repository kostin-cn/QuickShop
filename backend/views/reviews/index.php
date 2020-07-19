<?php

use yii\helpers\Html;
use sjaakp\sortable\SortableGridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Reviews */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-index">

    <p>
        <?= Html::a('Добавить отзыв', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin();?>
    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'orderUrl' => ['order'],
        'columns' => [
            'id',
            'name',
            'rate',
            [
                'attribute' => 'date',
                'format' =>  ['date', 'dd-MM-Y'],
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($data) {
                    if ($data->status) {
                        return Html::a("<span class = \"glyphicon glyphicon-ok\"></span> показано", Url::to(['status','id'=>$data->id]), ['class' => 'texts-success', 'data-method' => 'post']);
                    }
                    else {return Html::a("<span class='glyphicon glyphicon-remove'></span> скрыто", Url::to(['status', 'id'=>$data->id]), ['class' => 'texts-danger', 'data-method' => 'post']);}
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end();?>
</div>

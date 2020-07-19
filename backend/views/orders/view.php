<?php

use common\entities\Orders;
use common\entities\OrderItems;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\components\DropdownParams;

/* @var $this yii\web\View */
/* @var $model \common\entities\Orders */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$params = new DropdownParams();
?>
<div class="orders-view">

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'created_at:datetime',
            'quantity',
            'persons',
            'cost',
            'change_from',
            'name',
            'email:email',
            'address',
            'phone',
            [
                'attribute' => 'datetime',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    return $data->datetime ? Yii::$app->formatter->asDate($data->datetime, 'dd.MM.yyyy H:mm') : '';
                }
            ],
            'when_ready',
            [
                'attribute' => 'pay_method',
                'format' => 'raw',
                'value' => function (Orders $data) use ($params) {
                    return $params->pay_methods[$data->pay_method];
                },
            ],
            'note:ntext',
            [
                'attribute' => 'user_status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if (!$data->user_status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Да", '#', ['class' => 'text-success']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Нет', '#', ['class' => 'text-danger']);
                    }
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function (Orders $data) {
                    if ($data->status) {
                        return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Обработан", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                    }
                }
            ],
        ],
    ]) ?>

    <h3>Заказы</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Изображение',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return Html::img($data->product->image, [
                        'alt' => 'yii2 - картинка в gridview',
                        'style' => 'width:100px;'
                    ]);
                },
            ],
            [
                'label' => 'Наименование',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return Html::a($data->product->title, ['products/view', 'id' => $data->product_id]);
                },
            ],
            [
                'label' => 'Количество',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->qty_item;
                },
            ],
            [
                'label' => 'Цена',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->price_item;
                },
            ],
            [
                'label' => 'Сумма',
                'format' => 'raw',
                'value' => function (OrderItems $data) {
                    return $data->price_item * $data->qty_item;
                },
            ],
        ],
    ]); ?>
</div>

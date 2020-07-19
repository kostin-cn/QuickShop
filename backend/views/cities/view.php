<?php

use yii\helpers\Html;

use yii\widgets\DetailView;
use common\entities\Cities;

/* @var $this yii\web\View */
/* @var $model common\entities\Cities */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' =>
        'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
        'confirm' => 'Вы точно хотите удалить эту запись?',
        'method' => 'post',
        ],
        ]) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
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
            ],
            ]) ?>

        </div>
    </div>
</div>

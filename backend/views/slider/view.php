<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\entities\Slider;

/* @var $this yii\web\View */
/* @var $model common\entities\Slider */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Слайдер', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-view">

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
                [
                    'label' => 'Изображение',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->image_name) {

                            return Html::img($data->image, [
                                'alt' => 'yii2 - картинка в gridview',
                                'style' => 'width:300px;'
                            ]);
                        }
                        return false;
                    },
                ],
                'title_ru',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->status) {
                            return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Отображать", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span> Скрывать', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                        }
                    },
                    'options' => ['style' => 'width: 150px; max-width: 150px;'],
                ],
            ],
            ]) ?>

        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\entities\Events;

/* @var $this yii\web\View */
/* @var $model common\entities\Events */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-view">

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
                [
                    'label' => 'Логотип',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->logo) {

                            return Html::img($data->logo, [
                                'alt' => 'yii2 - картинка в gridview',
                                'style' => 'width:300px;'
                            ]);
                        }
                        return false;
                    },
                ],
                [
                    'label' => 'Иконка для карты',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->icon) {

                            return Html::img($data->icon, [
                                'alt' => 'yii2 - картинка в gridview',
                                'style' => 'width:300px;'
                            ]);
                        }
                        return false;
                    },
                ],
                [
                    'attribute' => 'cities_id',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->city->title_ru;
                    }
                ],
                'title_ru',
                'address_ru',
                'phone',
                [
                    'attribute' => 'short_descr_ru',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'description_ru',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'banquets_ru',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'lat',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'long',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'delivery_place',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'meta_keywords_ru',
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'meta_description_ru',
                    'format' => 'raw'
                ],
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

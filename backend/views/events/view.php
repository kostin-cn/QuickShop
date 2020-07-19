<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\entities\Events */

$this->title = $model->title_ru;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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
                        'attribute' => 'date',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDate($model->date, 'dd.MM.yyyy');
                        },
                    ],
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
                        'label' => 'Город',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->restaurant->city->title_ru;
                        }
                    ],
                    [
                        'attribute' => 'variants',
                        'value' => function ($data) {
                            return $data::VARIANTS[$data->variants];
                        }
                    ],
                    [
                        'attribute' => 'restaurants_id',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->restaurant->title_ru;
                        }
                    ],
                    'title_ru',
                    [
                        'attribute' => 'short_descr_ru',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'description_ru',
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
//                    'slug',

                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->status) {
                                return Html::a("<span class=\"glyphicon glyphicon-ok\"></span> Активная", Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-success btn-raised']);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove"></span> Архив', Url::to(['status', 'id' => $data->id]), ['class' => 'btn btn-danger btn-raised']);
                            }
                        },
                        'options' => ['style' => 'width: 150px; max-width: 150px;'],
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <?php if ($model->gallery) {
        echo '<h2>Галерея</h2><br>';
        foreach ($model->eventsAttachments as $attachment) {
            echo Html::img($attachment->getUrl(), ['style' => 'width:300px;']);
        }
    }; ?>


</div>

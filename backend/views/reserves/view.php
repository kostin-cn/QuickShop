<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Reserves;

/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->restaurant->title_ru, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

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
        <?php if ($model->status) {
            echo Html::a('<span class="glyphicon glyphicon-ok"></span> Подтвержден', ['status', 'id' => $model->id], ['class' => 'btn btn-success btn-raised pull-right']);
        } else {
            echo Html::a('<span class="glyphicon glyphicon-remove"></span> Ожидает', ['status', 'id' => $model->id], ['class' => 'btn btn-danger btn-raised pull-right']);
        }; ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Ресторан',
                        'value' => function ($model) {
                            return $model->restaurant->title_ru;
                        },
                    ],
                    'name',
                    'email:email',
                    'phone',
                    [
                        'attribute' => 'date',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Yii::$app->formatter->asDate($data->date, 'dd.MM.yyyy');
                        },
                    ],
                    'time',
                    'qty',
                    'notes:ntext',
//                    'dispatch',
//                    'language',
                ],
            ]) ?>

        </div>
    </div>
</div>

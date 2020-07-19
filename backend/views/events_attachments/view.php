<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\entities\Events_attachments;

/* @var $this yii\web\View */
/* @var $model common\entities\Events_attachments */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Events Attachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-attachments-view">

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
            'event_id',
            'path',
            'base_url:url',
            'type',
            'size',
            'name',
            'order',
            ],
            ]) ?>

        </div>
    </div>
</div>

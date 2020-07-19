<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\entities\Events_attachments;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events Attachments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-attachments-index">
        
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="box">
        <div class="box-body">
                            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                            'id',
            'event_id',
            'path',
            'base_url:url',
            'type',
            //'size',
            //'name',
            //'order',

                ['class' => 'yii\grid\ActionColumn'],
                ],
                ]); ?>
                                </div>
    </div>
</div>

<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Events_attachments */

$this->title = 'Изменить: ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => 'Events Attachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="events-attachments-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

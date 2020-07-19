<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Events_attachments */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Events Attachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-attachments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

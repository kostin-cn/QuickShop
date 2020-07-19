<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Events */

$this->title = 'Изменить: ' . $model->id;

$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="events-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

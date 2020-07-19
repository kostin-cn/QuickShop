<?php
/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryPlace */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Зона доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

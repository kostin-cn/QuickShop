<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Events */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => 'Рестораны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
/* @var $this yii\web\View */
/* @var $model common\entities\Reserves */
/* @var $restaurant common\entities\Contacts */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $restaurant->title_ru, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

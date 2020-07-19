<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\entities\Cities;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\entities\DeliveryPlace */
/* @var $form yii\widgets\ActiveForm */

$cities = ArrayHelper::map(Cities::find()->all(), 'id', 'title_ru');
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'lat')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'long')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

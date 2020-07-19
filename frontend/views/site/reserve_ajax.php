<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model frontend\forms\ReserveForm */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="order-form">

        <h3 class="title"><?= Yii::t('app', 'Make a reservation');?></h3>

        <?php $form = ActiveForm::begin(['options'=>['id'=>'reservation']]); ?>

        <?= $form->field($model, 'restaurant_id')->dropDownList($model->restArr(), ['prompt' => Yii::t('app', 'Select restaurant')])->label(false) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
            'mask' => '+9 (999) 999-99-99',
        ]) ?>

        <?= $form->field($model, 'date')->widget(
            DatePicker::class, [
            'language' => Yii::$app->language,
            'pluginOptions' => [
                'todayHighlight' => true,
                'todayBtn' => true,
                'autoclose' => true,
                'format' => 'dd.mm.yyyy',
            ]
        ]); ?>

        <?= $form->field($model, 'time_from')->input('time') ?>

        <?= $form->field($model, 'time_till')->input('time') ?>

        <?= $form->field($model, 'qty')->textInput() ?>

        <?= $form->field($model, 'notes')->textarea(); ?>


        <?= $form->field($model, 'data_collection_checkbox', [ 'checkboxTemplate'=> "{input}{label}\n{hint}\n{error}"])->checkbox(['checked ' => '','value' => true])->label(Yii::t('app','Consent to the processing of personal data').' *',['class'=>'check inline']);?>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app','Reserve') , ['class' => 'reserve']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>





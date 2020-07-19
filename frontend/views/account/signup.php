<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\SignupForm */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 shadowed">
                <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

                <p>Заполните следующие поля для регистрации:</p>

                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'cart-btn white btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>

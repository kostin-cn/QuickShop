<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\forms\PasswordResetRequestForm */

$this->title = 'Запрос сброса пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <div class="wrapper">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 shadowed">
                <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

                <p>Введите ваш E-mail, на него будет отправлена ссылка для сброса пароля.</p>

                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group text-center">
                    <?= Html::submitButton('Отправить', ['class' => 'cart-btn white btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</div>

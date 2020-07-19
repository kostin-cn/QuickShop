<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\forms\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h2 class="text-center"><?= Html::encode($this->title) ?></h2>

    <p>Заполните следующие поля для входа:</p>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div style="color:#999;margin:1em 0">
        Если вы забыли свой пароль, вы можете <?= Html::a('сбросить его', ['account/request-password-reset']) ?>.
    </div>
    <div style="color:#999;margin:1em 0">
        Если у вас нет аккаунта, <?= Html::a('зарегистрируйтесь', ['account/signup']) ?>.
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Войти', ['class' => 'cart-btn white btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use frontend\components\Service;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model \frontend\forms\AccountForm */
/* @var $user \common\entities\User */

$user = Yii::$app->user->identity;
?>

<div class="personal">
    <div class="wrapper">
        <h1>Личный кабинет</h1>
        <div class="link-holder">
            <a href="<?= Url::to(['account/logout']); ?>" data-method="POST" class="cart-btn white" style="margin-right: 20px">Выход</a>
            <a href="<?= Url::to(['account/request-password-reset']); ?>" data-method="POST" class="cart-btn white">Изменить пароль</a>
        </div>
    </div>


    <?php $form = ActiveForm::begin(); ?>
    <div class="wrapper wide">
        <div class="info-block">
            <div class="row">
                <div class="col-md-4">
                    <div class="personal-info">
                        <h2 class="block-title">Личные данные</h2>

                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                            'mask' => '+7 (999) 999-99-99',
                        ]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'birthday')->widget(
                            DatePicker::class, [
                            'language' => 'ru',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'todayBtn' => true,
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ]
                        ]); ?>

                        <div class="add-address-block">
                            <?php if (count($model->addresses) == 1): ; ?>
                                <h3>Адрес</h3>
                            <?php else: ; ?>
                                <h3>Адреса</h3>
                            <?php endif; ?>
                            <?php foreach ($model->addresses as $i => $address): ; ?>
                                <?= $form->field($address, '[' . $i . ']value')->textInput(['maxlength' => true])->label('') ?>
                            <?php endforeach; ?>

                            <div class="add-address">
                                <a href="<?= Url::to(['account/add-address']); ?>" class="cart-btn">Добавить другой адрес</a>
                            </div>

                            <hr>
                            <?= $form->field($model, 'get_email', [
                                'options' => ['class' => 'form-group data-checkbox'],
                                'checkboxTemplate' => "{input}{label}\n{hint}\n{error}"
                            ])->checkbox(); ?>
                            <?= $form->field($model, 'get_sms', [
                                'options' => ['class' => 'form-group data-checkbox'],
                                'checkboxTemplate' => "{input}{label}\n{hint}\n{error}"
                            ])->checkbox(); ?>
                            <p>
                                Обращаем Ваше внимание, что указывая свой номер телефона и осуществляя регистрацию на сайте, вы даете свое согласие на получение рекламных рассылок и иной информации рекламного содержания, нажав галочку в поле “Я хочу получать SMS уведомления”.
                            </p>
                            <p>
                                Вы вправе отказаться от получения рекламных рассылок, сняв галочку в поле “Я хочу получать SMS уведомления”.
                            </p>
                        </div>
                        <div class="submit-group">
                            <?= Html::submitButton('Сохранить изменения', ['class' => 'cart-btn white summit']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-7">
                    <div id="cart" class="history">
                            <h2 class="block-title">Мои заказы</h2>
                            <div class="order-info">
                                <div class="order-item">
                                    <div class="item-head item-row row">
                                        <div class="title col-sm-3 text-left">
                                            Дата
                                        </div>
                                        <div class="count col-sm-2 text-center">
                                            Количество товаров
                                        </div>
                                        <div class="price col-sm-2 text-left">
                                            Сумма
                                        </div>
                                        <div class="price col-sm-2 text-left">
                                            Статус
                                        </div>
                                        <div class="add-block col-sm-3 text-left"></div>
                                    </div>
                                    <?php if ($user->activeOrders): ; ?>
                                    <?php foreach ($user->activeOrders as $order): ; ?>
                                        <div class="item-info item-row row">
                                            <div class="title col-sm-3 text-left">
                                                <?= Yii::$app->formatter->asDate($order->created_at, 'd.MM.yyyy'); ?>
                                            </div>
                                            <div class="count col-sm-2 text-center">
                                                <div class="count-val">
                                                    <?= $order->quantity; ?>
                                                </div>
                                            </div>
                                            <div class="price col-sm-2 text-left">
                                                <?= $order->cost; ?> ₽
                                            </div>
                                            <div class="price col-sm-2 text-left">
                                                <?= Service::orderStatus($order->status); ?>
                                            </div>
                                            <div class="add-block col-sm-3 text-left">
                                                <a href="<?= Url::to(['account/order-view', 'id' => $order->id]); ?>"
                                                   class="cart-btn history-link line-link">Посмотреть заказ</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php else:;?>
                                    <div class="item-info item-row row">
                                        <div class="col-lg-12">
                                            <p><strong><?= Yii::t('app', 'В данный момент тут пусто, но никогда не поздно это исправить');?></strong></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php if ($user->activeOrders): ; ?>
                            <a href="<?= Url::to(['account/clear-history']); ?>" class="cart-btn line-link clear-btn"><span
                                        class="icon-delete"></span> Очистить список </a>
                        <?php endif; ?>

                        <div id="history_pop">
                            <div class="history-close magnet-btn">
                                <div class="ico">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="history-content"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
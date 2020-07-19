<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\components\Service;
use kartik\widgets\DateTimePicker;
use yii\widgets\MaskedInput;
use yii\captcha\Captcha;

/* @var $this yii\web\View
 * @var $model \frontend\forms\OrderForm
 * @var $form yii\bootstrap\ActiveForm
 * @var $cart \common\models\Cart
 */

$this->title = 'Оформить заказ';
?>

<div id="checkout">
    <div class="wrapper">

        <?php $form = ActiveForm::begin(['id' => 'checkout-form', 'options' => ['class' => 'styledForm']]); ?>

        <div class="checkout-form">

            <div class="checkout-item">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>Информация о покупателе</h2>
                        <?php if (Yii::$app->user->isGuest): ; ?>
                            <p style="color: darkred">На нашем сайте вы можете <a class="usrLogin" style="text-decoration: underline" href="<?= Url::to(['site/login']);?>">авторизоваться</a>, или
                                <a style="text-decoration: underline" href="<?= Url::to(['account/signup']);?>">зарегистрироваться</a></p>
                        <?php endif;?>
                        <!--                    --><? //= $form->field($model, 'customer_email')->hiddenInput()->label(false) ?>
                        <?= $form->field($model, 'customer_name')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'customer_phone')->widget(MaskedInput::class, [
                            'mask' => '+7 (999) 999-99-99',
                        ]) ?>
                        <?= $form->field($model, 'customer_email')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
                        <div id="delivery_datetime">
                            <div id="delivery_label">
                                <span>Время доставки</span>
                            </div>
                            <div id="delivery_date">
                                <?= $form->field($model, 'delivery_date')->widget(
                                    DateTimePicker::class, [
                                    'language' => 'ru',
                                    'options' => ['class' => 'form-control dtInput'],
                                    'type' => DateTimePicker::TYPE_INPUT,
                                    'pluginOptions' => [
                                        'startDate' => Yii::$app->formatter->asDatetime((time() + 30 * 60), 'dd-MM-yyyy H:mm'),
                                        'todayHighlight' => true,
                                        'todayBtn' => true,
                                        'autoclose' => true,
                                        'format' => 'dd-mm-yyyy H:ii',
                                        'hoursDisabled' => '0,1,2,3,4,5,6,7,8,9,10,11,23'
                                    ]
                                ])->label(false); ?>
                                <?= $form->field($model, 'whenReady', [
                                    'options' => ['class' => 'form-group data-checkbox'],
                                    'checkboxTemplate' => "{input}{label}\n{hint}\n{error}"
                                ])->checkbox(); ?>
                            </div>
                        </div>
                        <?= $form->field($model, 'persons')->textInput(['type'=>'number']) ?>
                        <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
                    </div>
                    <div class="col-lg-6">
                        <div class="checkout-item">
                            <h2>Способ оплаты</h2>
                        </div>

                        <div class="checkout-item">

                            <div class="checkout-radio">
                                <?= $form->field($model, 'payMethod')->radioList(Yii::$app->params['payMethods'], [
                                    'item' => function ($index, $label, $name, $checked, $value) use ($model) {
                                        $ch = $checked ? 'checked' : '';
                                        return
                                            '<div class="checkout_pay_input">
                                                  <div class="checkout_pay_radio_wrap">
                                                     <input type="radio" class="pay_radio" id="pay_method-' . $value . '" name="' . $name . '" value="' . $value . '" ' . $ch . ' />
                                                  </div>
                                                  <label for="pay_method-' . $value . '">' . $label . '</label>
                                              </div>';
                                    }
                                ])->label(false) ?>
                            </div>
                            <?= $form->field($model, 'change_from')->textInput(['type'=>'number']) ?>
                            <?= $form->field($model, 'data_collection_checkbox', [
                                'options' => ['class' => 'form-group data-checkbox'],
                                'checkboxTemplate' => "{input}{label}\n{hint}\n{error}"
                            ])->checkbox(); ?>

                            <?php if (Yii::$app->user->isGuest): ; ?>
                                <div class="captcha">
                                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                                        'captchaAction' => 'site/captcha',
                                        'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-7">{input}</div></div>',
                                    ]) ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">

                                <?php echo Html::submitButton('Оформить заказ', ['class' => 'cart-btn white']) ?>

                                <span class="cart-btn">
                                    <a href="<?= Url::to(['catalog/index']) ?>">
                                    <span class="icon-return"></span>
                                    <span class="cart-btn-text">Продолжить покупки</span>
                                    </a>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <div id="cart" style="padding-top: 0">
                <div class="order-item">
                    <div class="item-head item-row row">
                        <div class="title col-sm-4 text-left">
                            Наименование
                        </div>
                        <div class="count col-sm-4 text-center">
                            Количество
                        </div>
                        <div class="price col-sm-4 text-center">
                            Стоимость
                        </div>
                    </div>
                    <?php foreach ($cart->getItems() as $item):
                        /* @var  $item \common\models\CartItem */
                        $product = $item->getProduct();
                        ?>
                        <div class="item-info item-row row">
                            <div class="title col-sm-4 text-left">
                                <div class="image-block">
                                    <a class="image"
                                       href="<?= Url::to(['catalog/product', 'slug' => $product->slug]) ?>"
                                       style="background-image: url(<?= $product->image_name ? $product->image : '/files/default_thumb.png'; ?>)"></a>
                                </div>
                                <a href="<?= Url::to(['catalog/product', 'slug' => $product->slug]) ?>"><?= Html::encode($product->title) ?></a>
                            </div>

                            <div class="count col-sm-4 text-center">
                                <span class="circle cart-item-quantity"><?= $item->getQuantity() ?></span>
                            </div>

                            <div class="price col-sm-4 text-center">
                                <?= Service::formatPrice($item->getCost()) ?> ₽
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-summary item-row row">
                    <div class="price col-md-4 text-center">
                        <div class="cost-total">
                            <span>ОБЩАЯ СУММА: </span>
                            <?= $cart->getCost(); ?> ₽
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
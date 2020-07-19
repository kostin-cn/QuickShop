<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Service;

/* @var $this yii\web\View */
/* @var $cart \common\models\Cart */
$this->title = "Корзина";
?>
<div id="cart" class="page single-page">
    <div class="wrapper">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="order-item">
            <div class="item-head item-row row">
                <div class="title col-sm-4 text-left">
                    Наименование
                </div>
                <div class="count col-sm-3 text-center">
                    Количество
                </div>
                <div class="price col-sm-3 text-center">
                    Стоимость
                </div>
                <div class="add-block col-sm-2 text-center"></div>
            </div>
            <?php foreach ($cart->getItems() as $item):
                /* @var  $item \common\models\CartItem */
                $product = $item->getProduct();
                $url = Url::to(['catalog/product', 'slug' => $product->slug]);
                ?>
                <div class="item-info item-row row">
                    <div class="title col-sm-4 text-left">
                        <div class="image-block">
                            <a class="image" href="<?= $url ?>"
                               style="background-image: url(<?= $product->image_name ? $product->image : '/files/default_thumb.png'; ?>)"></a>
                        </div>
                        <a href="<?= $url ?>"><?= Html::encode($product->title) ?></a>
                    </div>

                    <div class="count col-sm-3 text-center">
                        <a class="circle cart-update"
                           href="<?= Url::to(['cart/update', 'id' => $item->getId(), 'inc' => -1]) ?>"
                           data-method="post">
                            <span class="icon-minus"></span>
                        </a>
                        <span class="circle cart-item-quantity"><?= $item->getQuantity() ?></span>
                        <a class="circle cart-update"
                           href="<?= Url::to(['cart/update', 'id' => $item->getId(), 'inc' => 1]) ?>"
                           data-method="post">
                            <span class="icon-plus"></span>
                        </a>
                    </div>

                    <div class="price col-sm-3 text-center">
                        <?= Service::formatPrice($item->getCost()) ?> ₽
                    </div>
                    <div class="add-block col-sm-2 text-center">
                        <a class="circle cart-remove"
                           href="<?= Url::to(['cart/remove', 'id' => $item->getId()]) ?>" data-method="post">
                            <span class="icon-delete"></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="order-summary item-row row">
            <div class="col-sm-3">
                <p>МИНИМАЛЬНАЯ СУММА ЗАКАЗА: <?= Yii::$app->params['minSum'];?> ₽</p>
            </div>
            <div class="price col-sm-6 text-center">
                <div class="cost-total">
                    <span>ОБЩАЯ СУММА: </span>
                    <?= $cart->getCost(); ?> ₽
                </div>
            </div>
            <div class="add-block col-sm-3 text-center">
                <?php if ($cart->getItems()): ?>
                <?php if ($cart->getCost() < Yii::$app->params['minSum']){?>
                        <span class="cart-btn red">Сумма заказа слишком низкая</span>
                <?php }else {?>
                        <a class="cart-btn white" href="<?= Url::to(['cart/checkout']) ?>">оформить заказ</a>
                <?php };?>
                <?php endif; ?>
            </div>
        </div>

        <div class="cart-btn-bottom">
                    <span class="cart-btn">
                        <button class="btn-cart-clear" type="button" href="<?= Url::to(['cart/clear']) ?>"
                                data-method="post">
                            <span class="icon-delete"></span>
                            <span class="cart-btn-text">очистить корзину</span>
                        </button>
                    </span>
            <span class="cart-btn">
                        <a href="<?= Url::to('/catalog/index') ?>">
                            <span class="icon-return"></span>
                            <span class="cart-btn-text"> продолжить покупки</span>
                        </a>
                    </span>
        </div>
    </div>
</div>


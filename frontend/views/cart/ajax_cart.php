<?php

use yii\helpers\Url;

/**
 * @var $message string
 * @var $data \common\entities\Products
 * @var $quantity integer
 */
?>

<div id="cartRender" data-amount="<?= Yii::$container->get('common\models\Cart')->getAmount(); ?>">
    <div class="cartTitle">
        <h5><?= $message; ?></h5>
    </div>
    <div class="cartItem">
        <div class="itemImage"
             style="background-image:url(<?= $data->image_name ? $data->image : '/files/default_thumb.png' ?>)"></div>
        <div class="itemDesc">
            <div class="itemName"><?= $data->title; ?></div>
            <div class="itemQty"><span class="circle bordered-red"><?= $quantity; ?></span> <?= $data->price; ?> ₽</div>
            <a class="cart-btn black" href="<?= Url::to(['cart/index']); ?>">Открыть корзину</a>
        </div>
    </div>

</div>

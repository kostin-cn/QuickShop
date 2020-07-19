<?php

use yii\helpers\Url;
use Yii;

/* @var $cart array */
/* @var $id int */
/* @var $sum int */
?>

<div id="cart">
    <div class="wrapper">
        <h1><?= Yii::t('app', 'Заказ №');?> <?= $id?></h1>
        <div class="order-info order-view">
            <?php if (!empty($cart)): ?>
                <div class="order-item jq_hidden">
                    <div class="item-head item-row row">
                        <div class="title col-sm-4 text-left">
                            Наименование
                        </div>
                        <div class="count col-sm-4 text-center">
                            Количество
                        </div>
                        <div class="price col-sm-4 text-left">
                            Стоимость
                        </div>
                    </div>
                    <?php foreach ($cart as $key => $item):; ?>
                        <div class="item-info item-row row">
                            <div class="title col-sm-4 text-left">
                                <div class="image-block">
                                    <div class="image cover-bg"
                                         style="background-image: url(<?= $item['image']; ?>)"></div>
                                </div>
                                <h3><?= $item['title']; ?></h3>
                            </div>
                            <div class="count col-sm-4 text-center">
                                <div class="count-val">
                                    <?= $item['quantity']; ?>
                                </div>
                            </div>
                            <div class="price col-sm-4 text-left">
                                <?= $item['cost'] ?> ₽
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="order-summary item-row jq_hidden row">
                <div class="title col-sm-4 text-center">
                </div>
                <div class="count col-sm-4 text-center">
                    ОБЩАЯ СУММА
                </div>
                <div class="price col-sm-4 text-left">
                    <?= $sum; ?> ₽
                </div>
            </div>

            <div class="add-links jq_hidden">
                <a class="cart-btn" href="<?= Url::to(['/account/index']);?>"><span class="icon-return"></span> <?= Yii::t('app', 'Назад');?></a>
                <a href="<?= Url::to(['cart/reorder', 'id' => $id]); ?>" class="cart-btn white">Повторить заказ</a>
            </div>
        </div>
    </div>
</div>

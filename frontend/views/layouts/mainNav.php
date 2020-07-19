<?php

use yii\helpers\Url;
use yii\widgets\Menu;
use common\widgets\WCity\WCity;

/* @var $menuItems array
 * @var $phone \common\entities\Contacts
 * @var $socials \common\entities\Socials
 * @var $categories \common\entities\ProductCategories
 * @var $category \common\entities\ProductCategories
 */

if (Yii::$app->user->isGuest) {
//    $menuItems[] = ['label' => Yii::t('app', 'Вход'), 'url' => ['site/login']];
} else {
//    $menuItems[] = ['label' => Yii::t('app', 'Личный кабинет'), 'url' => ['/account/index']];
}
//$menuItems[] = ['label' => Yii::t('app', 'Корзина'), 'url' => ['/cart/index']];

?>

<div id="menu">

    <a id="logo" class="mainLogo" href="<?= Url::to(['site/index']); ?>">
        <img src="/files/logo.svg" alt="">
    </a>

    <div class="mainNavBar">
        <?= Menu::widget([
            'items' => $menuItems,
            'options' => ['id' => 'mainNavMenu', 'class' => 'navMenu navigation'],
            'encodeLabels' => false,
        ]); ?>
        <div id="menu_button">
            <div class="burger">
                <span></span>
                <span class="mid"></span>
                <span class="mid"></span>
                <span></span>
            </div>
        </div>
    </div>
    <div class="topAccount">
        <div class="topCitySelect">

            <?= WCity::widget(); ?>
        </div>
        <?php if (Yii::$app->user->isGuest) {;?>
            <a class="usrBtn usrLogin" href="<?= Url::to(['site/login']);?>"><span class="icon-user"></span><span><?= Yii::t('app', 'Войти');?></span></a>
        <?php } else {;?>
            <a class="usrBtn" href="<?= Url::to(['/account/index']);?>"><span class="icon-user"></span><span><?= Yii::$app->user->identity->username;?></span></a>
        <?php };?>
        <a class="mainCartBtn" href="<?= Url::to(['/cart/index']);?>">
            <span class="icon-cart"></span>
            <?php
            /* @var $cart common\models\Cart */
            $cart = Yii::$container->get('common\models\Cart');
            if ($cart->getAmount()) {;?>
                <span id="cartCount" class="count visible"><?= $cart->getAmount(); ?></span>
            <?php }else {;?>
                <span id="cartCount" class="count">0</span>
            <?php };?>
        </a>
    </div>
    <a class="reserveBtn" href="<?= Url::to(['site/reserve']);?>"><?= Yii::t('app', 'Забронировать');?></a>
</div>
<div id="feedbackForm">
    <div id="closeFeedback" class="close">
        <span></span>
        <span></span>
    </div>
    <div id="feedbackContent"></div>
</div>
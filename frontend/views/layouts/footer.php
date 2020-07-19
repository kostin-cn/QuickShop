<?php
use yii\helpers\Url;
use yii\widgets\Menu;
use common\widgets\WCity\WCity;

/* @var $phone \common\entities\Contacts
* @var $address \common\entities\Contacts
 */
?>

<div id="footer">
    <div class="wrapper">

        <div class="footerContainer">
            <a class="mainLogo" href="<?= Url::to(['site/index']); ?>">
                <img src="/files/logo.svg" alt="">
            </a>

            <div class="mainNavBar">
                <?= Menu::widget([
                    'items' => $menuItems,
                    'options' => ['class' => 'navMenu navigation'],
                    'encodeLabels' => false,
                ]); ?>
            </div>
        </div>

        <div class="footerContainer">
            <div class="left">

            </div>

            <div class="right footer-flex">
                <?php if ($phone):;?>
                    <a href="tel:+<?= preg_replace('~[^0-9]+~', '', $phone->value); ?>"><span><?= $phone->value;?></span></a>
                <?php endif;?>
                <a class="reserveBtn" href="<?= Url::to(['site/reserve']);?>"><?= Yii::t('app', 'Забронировать');?></a>
            </div>
        </div>

        <div class="footerContainer">
            <div class="left">
                &copy; <?= Yii::$app->name; ?>, <?= date('Y'); ?> <?= Yii::t('app', 'г.'); ?>
            </div>
            <div class="right">
<!--                --><?//= Yii::t('app', 'Сделано в irestocracy'); ?>
                <div class="bottomCitySelect">
                    <?= WCity::widget(); ?>
                </div>
            </div>
        </div>
        <a href="//orphus.ru" id="orphus" data-email="<?= $customCity->feedback_email;?>" target="_blank"><img alt="Система Orphus" src="/files/orphus.gif" border="0" width="88" height="31" /></a>
    </div>
</div>

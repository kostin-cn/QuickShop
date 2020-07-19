<?php

use yii\helpers\Url;

/* @var $this yii\web\View
 * @var $slider \common\entities\Slider
 * @var $slide \common\entities\Slider
 * @var $categories \common\entities\ProductCategories
 * @var $category \common\entities\ProductCategories
 * @var $events \common\entities\Events
 * @var $event \common\entities\Events
 */

?>
<div id="index">
    <section class="index-section jq_hidden">
        <div id="mainSlider" class="slider">
            <div class="icon-arrow-left arrow left-arrow"><span class="icon-chevron-left"></span></div>
            <div class="icon-arrow-right arrow right-arrow"><span class="icon-chevron-right"></span></div>
            <?php foreach ($slider as $slide) {;?>
                <div class="slide respons__block" style="background-image: url('<?= $slide->image;?>')">
                    <div class="slide_title">
                        <h1><?= $slide->title;?></h1>
                    </div>
                </div>
            <?php };?>
        </div>
    </section>
    <section class="categories-section jq_hidden">
        <div class="main-cat-menu cat-menu">
            <h3 id="catalogCategoriesBtn" class="titleSection left"><?= Yii::t('app', 'МЕНЮ');?></h3>
            <ul>
                <?php foreach ($categories as $category) {;?>
                    <li>
                        <a href="<?= Url::to(['catalog/index', 'slug' => $category->slug]);?>"><?= $category->title;?></a>
                    </li>
                <?php };?>
            </ul>
        </div>
        <div id="mainCat" class="main-cat-item">
            <?php foreach ($categories as $category) {;?>
                <div class="categoryItem width-<?= $category->width;?> height-<?= $category->height;?>" style="background-image: url('<?= $category->image;?>')">
                    <div class="categoryTitle"><?= $category->title;?></div>
                    <div class="link-container">
                        <a href="<?= Url::to(['catalog/index', 'slug' => $category->slug]);?>" class="btn-link white"><?= Yii::t('app', 'Посмотреть');?></a>
                    </div>
                </div>
            <?php };?>
        </div>
        <div id="mainCatSlider" class="main-cat-item slider">
            <div class="icon-arrow-left arrow cat-left-arrow"><span class="icon-chevron-left"></span></div>
            <div class="icon-arrow-right arrow cat-right-arrow"><span class="icon-chevron-right"></span></div>
            <?php foreach ($categories as $category) {;?>
                <div class="categoryItem width-<?= $category->width;?> height-<?= $category->height;?>" style="background-image: url('<?= $category->image;?>')">
                    <div class="categoryTitle"><?= $category->title;?></div>
                    <div class="link-container">
                        <a href="<?= Url::to(['catalog/index', 'slug' => $category->slug]);?>" class="btn-link white"><?= Yii::t('app', 'Посмотреть');?></a>
                    </div>
                </div>
            <?php };?>
        </div>
    </section>
    <section class="events-section jq_hidden">
        <div class="wrapper">
            <h3 class="titleSection"><?= Yii::t('app', 'СОБЫТИЯ');?></h3>
            <div id="mainEvents" class="slider">
                <div class="icon-arrow-left arrow event-left-arrow"><span class="icon-chevron-left"></span></div>
                <div class="icon-arrow-right arrow event-right-arrow"><span class="icon-chevron-right"></span></div>
                <?php foreach ($events as $event) {;?>
                    <a class="eventItem" href="<?= Url::to(['site/event', 'slug' => $event->slug]);?>">
                        <div class="image" style="background-image: url('<?= $event->image;?>')"></div>
                        <div class="eventText">
                            <p class="eventDate"><?= Yii::$app->formatter->asDate($event->date, 'dd / MM / yyyy'); ?></p>
                            <h3><?= $event->title;?></h3>
                            <p><span>г.<?= $event->restaurant->title;?></span></p>
<!--                            <p>--><?//= $event->shortDescr;?><!--</p>-->
                        </div>
                    </a>
                <?php };?>
            </div>
            <div class="link-container">
                <a href="<?= Url::to(['site/events', 'slug' => 'all-events']);?>" class="btn-link red"><?= Yii::t('app', 'ЕЩЕ СОБЫТИЯ');?></a>
            </div>
        </div>
    </section>
</div>

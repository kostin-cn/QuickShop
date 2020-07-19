<?php

use common\entities\ProductCategories;
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $products \common\entities\Products[] */
/* @var $category \common\entities\ProductCategories */

$categories = ProductCategories::find()->where(['status' => 1])->orderBy('sort')->all();
$catItems = [];
foreach ($categories as $cat) {
    $catItems[] = ['label' => $cat->title, 'url' => ['catalog/index', 'slug' => $cat->slug]];
}

$this->title = $category->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="catalog">
    <div class="menu-category-group">
        <div class="menu-wrapper cat-menu">
            <h3 id="catalogCategoriesBtn">меню</h3>
            <?= Menu::widget([
                'items' => $catItems,
            ]); ?>
        </div>
        <div class="btn-category-group">
            <span class="btn-category-group-top"></span>
            <span class="btn-category-group-bottom"></span>
        </div>
    </div>

    <div class="menu-item-group flex-wrap">
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <h2><?= $category->title; ?></h2>
        <?php foreach ($products as $product): ; ?>
            <div class="block-table">
                <div class="menu-product">
                    <div class="flex-wrap column">
                        <a class="image-wrap"
                           href="<?= Url::to(['catalog/product', 'slug' => $product->slug]);?>"
                           style="background-image: url(<?= $product->image_name ? $product->image : '/files/default.jpg' ?>)">
                        </a>
                        <h3><a href="<?= Url::to(['catalog/product', 'slug' => $product->slug]);?>"><?= $product->title; ?></a></h3>
                    </div>
                    <div class="flex-wrap column">
                        <p><?= $product->price; ?> ₽</p>
                        <div class="buttonsTray">
                            <a class="cart-btn white cartBtn" href="<?= Url::to(['cart/minus', 'id' => $product->id]); ?>"><span class="icon-minus"></span></a>
                            <a class="cart-btn white wide cartBtn" href="<?= Url::to(['cart/plus', 'id' => $product->id]);?>">В Корзину</a>
                            <a class="cart-btn white cartBtn" href="<?= Url::to(['cart/plus', 'id' => $product->id]); ?>"><span class="icon-plus"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

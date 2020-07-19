<?php

use common\entities\ProductCategories;
use yii\widgets\Menu;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $product \common\entities\Products */
/* @var $menuRecommend \common\entities\Products */

$categories = ProductCategories::find()->where(['status' => 1])->orderBy('sort')->all();
$catItems = [];
foreach ($categories as $cat) {
    $catItems[] = ['label' => $cat->title, 'url' => ['catalog/index', 'slug' => $cat->slug]];
}

$this->title = $product->title;
$this->params['breadcrumbs'][] = ['label' => $product->category->title, 'url' => ['index', 'slug' => $product->category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="product">
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

    <div id="menu_item_about">
        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <div id="menu_item">

            <div class="menu-item-image">
                <div class="image-wrap"
                     style="background-image: url(<?= $product->image_name ? $product->image : '/files/default.jpg'; ?>)"></div>
            </div>

            <div class="menu-item-context">
                <div class="menu-item-title">
                    <h2>
                        <?= $product->title; ?>
                    </h2>
                </div>

                <?php if ($product->description) { ?>
                    <div class="menu-item-description">
                        <?= $product->description; ?>
                    </div>
                <?php }?>

                <h3>Количество:</h3>

                <div class="menu-item-count">

                    <?= Html::beginForm(['cart/add', 'id' => 'menu_item_form'], 'post'); ?>
                    <input type="hidden" value="<?= $product->id; ?>" name="id">
                    <div class="product-cart-add">

                        <label for="radio1" class="product-radio active">
                            <input type="radio" name="quantity" id="radio1" value="1" hidden checked>1</label>
                        <label for="radio2" class="product-radio">
                            <input type="radio" name="quantity" id="radio2" value="2" hidden>2</label>
                        <label for="radio3" class="product-radio">
                            <input type="radio" name="quantity" id="radio3" value="3" hidden>3</label>
                        <label for="radio4" class="product-radio">
                            <input type="radio" name="quantity" id="radio4" value="4" hidden>4</label>
                        <label for="radio5" class="product-radio">
                            <input type="radio" name="quantity" id="radio5" value="5" hidden>5</label>
                        <input type="text" name="quantity-input" placeholder="<?= Yii::t('app', 'Другое'); ?>"
                               class="product-input"/>
                    </div>

                    <div class="menu-item-price">
                        <!--                    <h1><span id="menu_item_cost">-->
                        <? //= $menu_item->price;?><!--</span> <i class="fa fa-rub" aria-hidden="true"></i></h1>-->
                        <!--                    <h1><span id="menu_item_cost">-->
                        <? //= Service::formatPrice($menu_item->price);?><!--</span></h1>-->
                        <span id="for-js-cost" style="display: none"><?= $product->price; ?></span>
                        <span id="menu_item_cost"><?= $product->price; ?></span> ₽
                    </div>

                </div>
                <?= Html::submitButton('В Корзину', ['class' => 'cart-btn white wide']) ?>
                <?= Html::endForm() ?>

                <div class="menu-item-back">
                    <a href="<?= Url::to(['catalog/index', 'slug' => $product->category->slug]); ?>">вернуться</a>
                </div>
            </div>
        </div>

        <div class="menu-item-recommend flex-wrap">

            <h3>Рекомендуем</h3>
            <?php foreach ($menuRecommend as $item) { ?>
                <div class="block-table ">
                    <div class="menu-product">
                        <div class="flex-wrap column">
                            <a class="image-wrap"
                               href="<?= Url::to(['catalog/product', 'slug' => $item->slug]);?>"
                               style="background-image: url(<?= $item->image_name ? $item->image : '/files/default.jpg' ?>)">
                            </a>
                            <h3><a href="<?= Url::to(['catalog/product', 'slug' => $item->slug]);?>"><?= $item->title; ?></a></h3>
                        </div>
                        <div class="">
                            <p><?= $item->price;?> ₽</p>
<!--                            <a class="cart-btn white wide" href="--><?//= Url::to(['catalog/product', 'slug' => $item->slug]); ?><!--">В Корзину</a>-->
                            <a class="cart-btn white wide cartBtn" href="<?= Url::to(['cart/plus', 'id' => $item->id]);?>">В Корзину</a>
                        </div>
                    </div>
                </div>
            <?php }; ?>
        </div>
    </div>
</div>

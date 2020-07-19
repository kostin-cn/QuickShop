<?php

use yii\helpers\Url;
use common\entities\ProductCategories;
use common\entities\Restaurants;
use common\entities\Cities;
use yii\helpers\ArrayHelper;

/* @var $user \common\entities\User */
/* @var $productCategory \common\entities\ProductCategories */
/* @var $productItems array */
/* @var $restaurants Restaurants[] */
/* @var $cities Cities[] */
/* @var $schoolItems array */

$user = Yii::$app->user->identity;
$restaurants = Restaurants::find()->all();
$cities = Cities::find()->orderBy(['sort' => SORT_ASC])->all();

$productCategories = ProductCategories::find()->andWhere(['status' => 1])->orderBy('sort')->all();

$reservesArr = [];
foreach ($restaurants as $restaurant) {
    $reservesIds = ArrayHelper::getColumn($restaurant->getReserves(), 'id');
    $reservesArr[] = [
        'label' => $restaurant->title_ru,
        'icon' => ((Yii::$app->controller->actionParams['restaurant_id'] == $restaurant->id or in_array(Yii::$app->controller->actionParams['id'], $orderIds)) && Yii::$app->controller->id == 'reserves') ? 'folder-open' : 'folder',
        'active' => ((Yii::$app->controller->actionParams['restaurant_id'] == $restaurant->id or in_array(Yii::$app->controller->actionParams['id'], $orderIds)) && Yii::$app->controller->id == 'reserves'),
        'url' => ['/reserves', 'restaurant_id' => $restaurant->id]
    ];
}
foreach ($cities as $city) {
    $deliveryArr[] = [
        'label' => $city->title_ru,
        'icon' => ((Yii::$app->controller->actionParams['city_id'] == $city->id or in_array(Yii::$app->controller->actionParams['id'], $orderIds)) && Yii::$app->controller->id == 'delivery-place') ? 'folder-open' : 'folder',
        'active' => ((Yii::$app->controller->actionParams['city_id'] == $city->id or in_array(Yii::$app->controller->actionParams['id'], $orderIds)) && Yii::$app->controller->id == 'delivery-place'),
        'url' => ['/delivery-place', 'city_id' => $city->id]
    ];
}
foreach ($productCategories as $productCategory) {
    $ids = ArrayHelper::getColumn($productCategory->products, 'id');
    $productItems[] = ['label' => $productCategory->title,
        'icon' => (
            ($this->context->id == 'products' && Yii::$app->controller->actionParams['slug'] == $productCategory->slug) or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ) ? 'folder-open' : 'folder',
        'active' => (
            Yii::$app->controller->actionParams['slug'] == $productCategory->slug or
            ($this->context->id == 'products' && in_array(Yii::$app->controller->actionParams['id'], $ids))
        ),
        'url' => ['/products', 'slug' => $productCategory->slug]];
}

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->userProfile->getAvatar('/files/anonymous.jpg') ?>"
                     class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->getPublicIdentity() ?></p>
                <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                    <i class="fa fa-circle text-success"></i>
                    <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                </a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Редактор', 'options' => ['class' => 'header']],
//                    ['label' => 'Файл-менеджер', 'icon' => 'file-image-o', 'url' => ['/file-manager']],
                    ['label' => 'Резерв', 'icon' => 'calendar', 'url' => '#', 'items' => $reservesArr],
                    ['label' => 'Отзывы', 'icon' => 'pencil-square-o', 'active' => ($this->context->id == 'reviews'), 'url' => ['/reviews']],
                    ['label' => 'Магазин',
                        'icon' => (
                            $this->context->id == 'product-categories'
                            or $this->context->id == 'products'
                        ) ? 'folder-open' : 'folder', 'url' => '#',
                        'items' => [
                            ['label' => 'Категории', 'icon' => 'bars', 'active' => ($this->context->id == 'product-categories'), 'url' => ['/product-categories']],
                            ['label' => 'Товары', 'icon' => ($this->context->id == 'products') ? 'folder-open' : 'folder', 'url' => '#',
                                'items' => $productItems
                            ],
                            ['label' => 'Заказы', 'icon' => 'exclamation-triangle', 'active' => ($this->context->id == 'orders'), 'url' => ['/orders']],
                        ]
                    ],
                    ['label' => 'Главная', 'icon' => 'folder',
                        'items' => [
                            ['label' => 'Слайдер', 'icon' => 'image', 'active' => ($this->context->id == 'slider'), 'url' => ['/slider']],
                            ['label' => 'SEO', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'index'), 'url' => ['/seo/view', 'page' => 'index']],
                        ]
                    ],
                    ['label' => 'О Доставке', 'icon' => 'info', 'active' => ($this->context->id == 'modules'), 'url' => ['/modules/view', 'id' => 1]],
                    ['label' => 'Города', 'icon' => 'industry', 'active' => ($this->context->id == 'cities'), 'url' => ['/cities']],
                    ['label' => 'Зона доставки', 'icon' => 'industry', 'items' => $deliveryArr],
                    ['label' => 'Рестораны', 'icon' => 'folder',
                        'items' => [
                            ['label' => 'Рестораны', 'icon' => 'newspaper-o', 'active' => ($this->context->id == 'restaurants'), 'url' => ['/restaurants']],
                            ['label' => 'SEO', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'restaurants'), 'url' => ['/seo/view', 'page' => 'restaurants']],
                        ]
                    ],
                    ['label' => 'События', 'icon' => 'folder',
                        'items' => [
                            ['label' => 'События', 'icon' => 'newspaper-o', 'active' => ($this->context->id == 'events'), 'url' => ['/events']],
                            ['label' => 'SEO', 'icon' => 'folder',
                                'items' => [
                                    ['label' => 'SEO События', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'all-events'), 'url' => ['/seo/view', 'page' => 'all-events']],
                                    ['label' => 'SEO Концерты', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'concerts'), 'url' => ['/seo/view', 'page' => 'concerts']],
                                    ['label' => 'SEO Акции', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'events'), 'url' => ['/seo/view', 'page' => 'events']],
                                    ['label' => 'SEO Детям', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'kids'), 'url' => ['/seo/view', 'page' => 'kids']],
                                    ['label' => 'SEO Новости', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo' && Yii::$app->controller->actionParams['page'] == 'news'), 'url' => ['/seo/view', 'page' => 'news']],
                                ]],
                        ]
                    ],
//                    ['label' => 'Модули', 'icon' => 'file-code-o', 'active' => ($this->context->id == 'modules'), 'url' => ['/modules']],
                    ['label' => 'Контакты', 'icon' => 'address-book-o', 'active' => ($this->context->id == 'contacts'), 'url' => ['/contacts']],
                    ['label' => 'Соцсети', 'icon' => 'facebook', 'active' => ($this->context->id == 'socials'), 'url' => ['/socials']],
                    ['label' => 'SEO', 'icon' => 'file-code-o', 'active' => (Yii::$app->controller->id == 'seo'), 'url' => ['/seo']],
                ]
            ]
        ) ?>
    </section>
</aside>

<?php

use common\entities\ProductCategories;
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\entities\Socials;
use common\entities\Contacts;
use common\entities\Cities;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$cities = Cities::getDb()->cache(function () {
    return Cities::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);
$customCity = Cities::getDb()->cache(function () {
    return Cities::find()->where(['slug' => Yii::$app->params['customCity']])->having(['status' => 1])->one();
}, Yii::$app->params['cacheTime']);

$socials = Socials::getDb()->cache(function () {
    return Socials::find()->having(['status' => 1])->orderBy('sort')->all();
}, Yii::$app->params['cacheTime']);

$address = Contacts::getDb()->cache(function () {
    return Contacts::find()->where(['type' => 'point'])->having(['status' => 1])->one();
}, Yii::$app->params['cacheTime']);

$phone = Contacts::getDb()->cache(function () {
    return Contacts::find()->where(['type' => 'phone'])->having(['status' => 1])->one();
}, Yii::$app->params['cacheTime']);

$categories = ProductCategories::find()->where(['status' => 1])->orderBy('sort')->all();
$catItems = [];
foreach ($categories as $category) {
    $catItems[] = ['label' => $category->title, 'url' => ['catalog/index', 'slug' => $category->slug]];
}
$menuItems = [
    ['label' => Yii::t('app', 'Меню'), 'options' => ['class' => ['menu_category_dropdown']],
        'active' => ($this->context->id == 'catalog'),
        'url' => ['catalog/index'],
        'items' => $catItems],
    ['label' => Yii::t('app', 'Акции'), 'url' => ['site/events', 'slug' => 'events']],
    ['label' => Yii::t('app', 'События'), 'url' => ['site/events', 'slug' => 'all-events']],
    ['label' => Yii::t('app', 'Доставка'), 'url' => ['site/about']],
    ['label' => Yii::t('app', 'Отзывы'), 'url' => ['site/add-review'], 'options' => ['id' => 'feedback']],
    ['label' => Yii::t('app', 'Контакты'), 'url' => ['site/contacts']],
];
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="main">
    <?= Alert::widget() ?>

    <div id="content" class="contentWrapper">
        <div id="mainNav">
            <div class="wrapper">
                <?= $this->render('mainNav', [
                    'menuItems' => $menuItems,
                    'catItems' => $catItems,
                    'socials' => $socials,
                    'phone' => $phone,
                    'cities' => $cities,
                    'customCity' => $customCity,
                ]); ?>
            </div>
        </div>
        <div id="cartPopUp">
            <div id="cartClose"></div>
            <div id="cartData"></div>
        </div>

        <?= $content ?>
    </div>

    <?= $this->render('footer', [
        'menuItems' => $menuItems,
        'catItems' => $catItems,
        'socials' => $socials,
        'phone' => $phone,
        'address' => $address,
        'cities' => $cities,
        'customCity' => $customCity,
    ]); ?>
</div>

<div id="pop-up" class="pop-up">
    <div class="pop-content">
        <div class="pop-close">
            <span></span>
            <span></span>
        </div>
        <div id="pop-content">

        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

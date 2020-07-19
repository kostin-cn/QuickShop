<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $events \common\entities\Events[]
 * @var $event \common\entities\Events[]
 * @var $pages /frontend/controllers/SiteController
 */
?>

<div class="row articles-container">
    <?php foreach ($events as $key => $event) : ?>
        <a class="item article-block col-lg-3 col-md-4 col-sm-6 ani-block jq_hidden"
           href="<?= Url::to(['event', 'slug' => $event->slug]) ?>">
            <div class="image" style="background-image: url(<?= $event->image; ?>)"></div>
            <div class="item-text">
                <p class="eventDate"><?= Yii::$app->formatter->asDate($event->date, 'dd.MM.yyyy'); ?></p>
                <p><span><?= $event->restaurant->title;?></span></p>
                <h3 class="subtitle-shortline"><?= $event->title; ?></h3>
                <div class="desc"><?= $event->shortDescr; ?></div>
            </div>
        </a>
        <?php if ($key % 4 == 3) { ?>
            <div class="clearfix visible-lg-block"></div><?php }
    ; ?>
        <?php if ($key % 2 == 1) { ?>
            <div class="clearfix visible-md-block"></div><?php }; ?>
    <?php endforeach; ?>
</div>
<div id="pagination">
    <?= LinkPager::widget([
        'pagination' => $pages,
        'options' => [
            'class' => 'news-more',
            'firstPageLabel' => '',
            'lastPageLabel' => '',
            'prevPageLabel' => '',
            'nextPageLabel' => Yii::t('app', 'загрузить еще'),

            'pageCssClass' => 'news-more',
            'prevPageCssClass' => '',
            'nextPageCssClass' => 'next_s',

            'firstPageCssClass' => 'lknflbes',
            'maxButtonCount' => 1,
        ]
    ]); ?>
</div>

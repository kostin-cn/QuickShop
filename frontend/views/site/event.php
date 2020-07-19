<?php

/* @var $event \common\entities\Events */

use yii\helpers\Url;
$this->title = $event->title;
?>

<div id="event">
    <div class="content">
        <div class="wrapper w-1200 jq_hidden">
            <img src="<?= $event->image ?>" alt="" class="content-image">
            <div class="description">
                <h1><?= $event->title; ?></h1>
                <div class="short">
                    <?= $event->shortDescr; ?>
                </div>
                <?= $event->description; ?>
                <div>
                    <?php if ($event->variants) {;?>
                        <a href="<?= Url::to(['site/events', 'slug' => 'all-events']) ?>" class="go-back ctrl-btn">
                            <span class="ico icon-return"></span>
                            <span><?= Yii::t('app', 'Назад');?></span>
                        </a>
                    <?php }else {;?>
                        <a href="<?= Url::to(['site/events', 'slug' => 'all-events']) ?>" class="go-back ctrl-btn">
                            <span class="ico icon-return"></span>
                            <span><?= Yii::t('app', 'Назад');?></span>
                        </a>
                    <?php };?>
                </div>
            </div>
        </div>
    </div>
</div>
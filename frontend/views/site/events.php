<?php

use yii\widgets\Menu;

/**
 * @var $events \common\entities\Events[]
 * @var $event \common\entities\Events[]
 * @var $pages /frontend/controllers/SiteController
 * @var $restaurants \common\entities\Restaurants[]
 * @var $restaurant \common\entities\Restaurants[]
 * @var $cities \common\entities\Cities
 * @var $city \common\entities\Cities
 */
?>
<div id="events">
    <div class="wrapper">
        <h1 class="big_title jq_hidden"><?= Yii::t('app', $slug);?></h1>
    </div>
    <div class="wrapper">
        <div class="dropdownContainer">
<!--            <div class="selectItemBg">-->
<!--                <select class="selectItem" name="vars" id="varEvnt">-->
<!--                    --><?php //foreach (\common\entities\Events::VARIANTS as $key=>$variant) {;?>
<!--                        <option class="optionItem optionVarItem show" value="--><?//= $key;?><!--">--><?//= $variant;?><!--</option>-->
<!--                    --><?php //};?>
<!--                </select>-->
<!--            </div>-->
<!--            <div class="selectItemBg">-->
<!--                <select class="selectItem" name="rest" id="restEvnt" data-href="--><?//= Url::to(['site/event-list']);?><!--">-->
<!--                    --><?php //foreach ($restaurants as $restaurant) {;?>
<!--                        <option class="optionItem optionRestItem --><?//= $restaurant->cities_id == 1 ? 'show' : '';?><!--"-->
<!--                                value="--><?//= $restaurant->id;?><!--"-->
<!--                                data-city="--><?//= $restaurant->cities_id;?><!--">-->
<!--                            --><?//= $restaurant->title;?>
<!--                        </option>-->
<!--                    --><?php //};?>
<!--                </select>-->
<!--            </div>-->
            <?= Menu::widget([
                'items' => [
                    ['label' => Yii::t('app', 'Концерты'), 'url' => ['site/events', 'slug' => 'concerts']],
                    ['label' => Yii::t('app', 'Акции'), 'url' => ['site/events', 'slug' => 'events']],
                    ['label' => Yii::t('app', 'Детям'), 'url' => ['site/events', 'slug' => 'kids']],
                    ['label' => Yii::t('app', 'Новости'), 'url' => ['site/events', 'slug' => 'news']],
                ],
                'options' => ['class' => 'eventsMenu navigation'],
                'encodeLabels' => false,
            ]); ?>
        </div>
    </div>
    <div id="pageContainer">
        <?= $this->render('_event_list', [
            'events' => $events,
            'pages' => $pages,
        ]); ?>
    </div>
</div>

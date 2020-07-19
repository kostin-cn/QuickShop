<?php

/**
 * @var $contacts \common\entities\Contacts[]
 * @var $contact \common\entities\Contacts[]
 * @var $restaurants \common\entities\Restaurants[]
 * @var $restaurant \common\entities\Restaurants[]
 * @var $cities \common\entities\Cities[]
 */
use frontend\assets\ContactsAsset;
ContactsAsset::register($this);
?>
<div id="contacts">
    <div class="info">
        <div class="info-wrapper">
            <h1><?= Yii::t('app', 'КОНТАКТЫ');?></h1>
            <div class="contacts-container">
                <?php foreach ($restaurants as $key => $restaurant) {;?>
                    <div class="contacts-block">
                        <h3 class="map-this-to-center"
                            href="#map"
                            data-lat="<?= $restaurant->lat;?>"
                            data-lng="<?= $restaurant->long;?>"
                            data-place="<?= $restaurant->delivery_place;?>"
                            data-key="<?= $key;?>"
                            data-addr="<?= $restaurant->address;?>"
                            data-marker="icon_o">
                            <?= $restaurant->title;?>
                        </h3>
                        <?php if ($restaurant->address_ru) {;?>
                            <div class="contactItem jq_hidden"><span class="contacts-ico icon-point"></span><?= $restaurant->address_ru;?></div>
                        <?php };?>
                        <?php if ($restaurant->phone) {;?>
                            <div class="contactItem jq_hidden"><span class="contacts-ico icon-phone"></span><?= $restaurant->phone;?></div>
                        <?php };?>
                    </div>
                <?php };?>
                <?php foreach ($cities as $city_key=>$city) {;?>
                    <?php
                        $deliveryPlace = $city->getDeliveryPlace($city->id);
                        foreach ($deliveryPlace as $dlv_key=>$place) {
                            $deliveryArr[$city_key][$dlv_key] = [
                                lat => $place->lat,
                                lng => $place->long,
                            ];
                        };?>
                <?php };?>
                <div class="map-delivery-place" data-place='<?= json_encode($deliveryArr);?>'></div>
            </div>
        </div>
    </div>
    <div id="map"></div>
</div>


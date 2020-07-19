<?php
namespace common\widgets\WCity;

use common\entities\Cities;
use yii\helpers\Html;
use Yii;

/* @var $current Cities */
/* @var $cities Cities[] */

?>

<div class="city">
    <span class="current-city">
        <?= $current->title;?>
    </span>
    <ul class="cities">
        <?php foreach ($cities as $city):?>
            <li class="item-city <?= $city->url == $current->url ? 'current' : '';?>">
                <?= Html::a($city->title, '/'.$city->url.Yii::$app->getRequest()->getCityUrl()) ?>
            </li>
        <?php endforeach;?>
    </ul>
</div>
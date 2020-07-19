<?php
/* @var $about \common\entities\Modules */

?>
<div id="about">
    <div class="infoBlock aboutBlock jq_hidden">
        <div class="scrollFrame">
            <div class="aboutContainer">
                <h1><?= $about->title;?></h1>
                <?= $about->html;?>
            </div>
        </div>
    </div>
    <div class="imageBlock aboutBlock" style="background-image: url(<?= $about->image;?>)"></div>
</div>

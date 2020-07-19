<?php
namespace common\widgets\MultiLang;

use yii\helpers\Url;
use Yii;

?>

<div class="lang">
    <div id="currentLang">
        <?= Yii::$app->params['languages'][Yii::$app->language];?> <span class="icon-arrow-rounded-down"></span>
    </div>
    <ul class="language-choosing" id="langList">
        <?php foreach (Yii::$app->params['languages'] as $key => $lang ) { ?>
            <li class="<?= Yii::$app->language == $key ? 'active' : ''; ?>">
                <a href="<?= Url::to(array_merge(
                    \Yii::$app->request->get(),
                    [\Yii::$app->controller->route, 'language' => $key]
                )) ?>"
                   class="item-lang <?= Yii::$app->language == $key ? 'active' : ''; ?>">
                    <?= $lang;?>
                </a>
            </li>
        <?php }?>
    </ul>
</div>
<?php
namespace common\widgets\WCity;
use common\entities\Cities;

class WCity extends \yii\bootstrap\Widget
{
    public function init(){}

    public function run() {
        return $this->render('view', [
            'current' => Cities::getCurrent(),
            'cities' => Cities::find()->where(['status' => 1])->orderBy('sort')->all(),
        ]);
    }
}
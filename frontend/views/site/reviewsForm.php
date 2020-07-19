<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\entities\Reviews */
/* @var $form ActiveForm */

$radio_array = [
    0 => '<i class="icon-star-o"></i>',
    1 => '<i class="icon-star-o"></i>',
    2 => '<i class="icon-star-o"></i>',
    3 => '<i class="icon-star-o"></i>',
    4 => '<i class="icon-star-o"></i>',
];
?>
<div class="reviewsForm">
    <h3><?= Yii::t('app', 'Оставьте свой отзыв');?></h3>

    <?php $form = ActiveForm::begin(['id' => 'reviewsForm', 'action' => '/site/add-review']); ?>

    <div class="site-reviews-name">
        <?= $form->field($model, 'name')->textInput(['placeholder' => Yii::t('app',''),'maxlength' => true,'minlength' => true])->label(Yii::t('app','Ваше имя')) ?>
    </div>

    <div class="site-reviews-radiolist">
        <?= $form->field($model, 'rate', ['options'=>['id'=>'custom_radio_list']])->radioList($radio_array,['encode'=>FALSE])->label(Yii::t('app','Ваша оценка')) ?>
    </div>

    <div class="site-reviews-description">
        <?= $form->field($model, 'description')->textarea(['placeholder' => Yii::t('app',''), 'rows' => 6])->label(Yii::t('app','Ваш отзыв')) ?>
    </div>


            <div class="captcha">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'captchaAction' => 'site/captcha',
                    'template' => '<div class="row"><div class="col-lg-4">{image}</div><div class="col-lg-7">{input}</div></div>',
                ]) ?>
            </div>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Оставить отзыв'), ['class' => 'cart-btn black btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>

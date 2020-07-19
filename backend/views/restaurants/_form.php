<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use common\entities\Cities;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\entities\Restaurants */
/* @var $form yii\widgets\ActiveForm */

$cities = ArrayHelper::map(Cities::find()->all(), 'id', 'title_ru');
?>

<div class="events-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-lg-4">
                    <?php
                    $img = ($model->image_name) ? $model->image : '/files/default_thumb.png';
                    $label = Html::img($img, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Изображение</span>";
                    ?>
                    <?= $form->field($model, 'uploadedImageFile', ['options' => ['class' => 'form-group img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>
                </div>
                <div class="col-lg-4">
                    <?php
                    $logo = ($model->logo_name) ? $model->logo : '/files/default_thumb.png';
                    $label = Html::img($logo, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Логотип</span>";
                    ?>
                    <?= $form->field($model, 'uploadedLogoFile', ['options' => ['class' => 'form-group img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>
                </div>
                <div class="col-lg-2">
                    <?php
                    $icon = ($model->icon_name) ? $model->icon : '/files/default_thumb.png';
                    $label = Html::img($icon, ['class' => 'preview_image_block', 'alt' => 'Image of ' . $model->id]) . "<span>Иконка для карты</span>";
                    ?>
                    <?= $form->field($model, 'uploadedIconFile', ['options' => ['class' => 'form-group img_input_block']])
                        ->fileInput(['class' => 'hidden-input img-input', 'accept' => 'image/*'])->label($label, ['class' => 'label-img']); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'cities_id')->dropDownList($cities, ['prompt' => 'Выберите город...']) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'address_ru')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'short_descr_ru')->textarea(['rows' => 9, 'maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'description_ru')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 150,
                        ]
                    ]); ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'banquets_ru')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 150,
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'lat')->textInput() ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'long')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'delivery_place')->textarea(['rows' => 5]) ?>
                    <p style="color: red">
                        Это поле очень требовательно к правильности ввода данных
                    </p>
                </div>
            </div>
            <h3 style="font-size: 3em">SEO</h3>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'meta_keywords_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'meta_description_ru')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

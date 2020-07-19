<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use vova07\imperavi\Widget;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use common\entities\Restaurants;

/* @var $this yii\web\View */
/* @var $model common\entities\Events */
/* @var $form yii\widgets\ActiveForm */


$resraurants = ArrayHelper::map(Restaurants::find()->all(), 'id', 'title_ru', function ($item){return $item->city->title;})
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
                    <?= $form->field($model, 'date')->widget(
                        DatePicker::class, [
                        'language' => 'ru',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                        ]
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2">
                    <?= $form->field($model, 'restaurants_id')->dropDownList($resraurants, ['prompt' => 'Выберите ресторан...']) ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'variants')->dropDownList($model::VARIANTS) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'short_descr_ru')->textarea(['rows' => 3, 'maxlength' => true]) ?>
                    <?= $form->field($model, 'description_ru')->widget(Widget::class, [
                        'settings' => [
                            'lang' => 'ru',
                            'minHeight' => 150,
                        ]
                    ]); ?>
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

    <?= $form->field($model, 'attachments')->widget(
        'backend\components\widget\GalleryUpload',
        [
            'url' => ['file-storage/upload'],
            'sortable' => true,
            'maxFileSize' => 10 * 1024 * 1024, // 10 MiB
            'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'maxNumberOfFiles' => 10,
            'clientOptions' => []
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

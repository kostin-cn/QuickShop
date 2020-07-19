<?php

namespace frontend\controllers;

use common\entities\Products;
use common\entities\ProductCategories;
use frontend\components\FrontendController;
use yii\web\NotFoundHttpException;

class CatalogController extends FrontendController
{
    public function actionIndex($slug = null)
    {
        if ($slug) {
            if (!$category = ProductCategories::findOne(['slug' => $slug])) {
                throw new NotFoundHttpException('Запрошенная вами страница не существует.');
            }
        } else {
            $category = ProductCategories::find()->having(['status' => 1])->orderBy(['sort' => SORT_ASC])->one();
        }
        $this->setMeta($category->title);
        $products = Products::find()->having(['category_status' => 1, 'status' => 1])->andWhere(['category_id' => $category->id])->orderBy('sort')->all();

        return $this->render('index', [
            'products' => $products,
            'category' => $category,
        ]);
    }

    public function actionProduct($slug)
    {
        /* @var $item Products */
        if (!$product = Products::findOne(['slug' => $slug])) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $menuRecommend = Products::find()->having(['status' => 1])->andWhere(['not', ['slug' => $slug]])->andWhere(['category_id' => $product->category_id])
            ->orderBy(['id' => SORT_DESC])->limit(4)->all();
        return $this->render('product', [
            'product' => $product,
            'menuRecommend' => $menuRecommend
        ]);
    }
}
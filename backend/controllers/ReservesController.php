<?php

namespace backend\controllers;

use common\entities\Restaurants;
use Yii;
use common\entities\Reserves;
use backend\forms\ReservesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReservesController implements the CRUD actions for Reserves model.
 */
class ReservesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($restaurant_id)
    {
        if (!$restaurant = Restaurants::findOne($restaurant_id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $searchModel = new ReservesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurant' => $restaurant,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($restaurant_id)
    {
        if (!$restaurant = Restaurants::findOne($restaurant_id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $model = new Reserves();
        $model->restaurant_id = $restaurant->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $restaurant_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'restaurant' => $restaurant,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurant_id' => $model->restaurant_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'restaurant_id' => $model->restaurant_id]);
    }

    protected function findModel($id)
    {
        if (($model = Reserves::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная вами страница не существует.');
    }
}

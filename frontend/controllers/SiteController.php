<?php
namespace frontend\controllers;

use common\entities\Cities;
use common\entities\Contacts;
use common\entities\Events;
use common\entities\Modules;
use common\entities\ProductCategories;
use common\entities\Restaurants;
use common\entities\Reviews;
use common\entities\Slider;
use Yii;
use common\forms\LoginForm;
use frontend\forms\ReserveForm;
use yii\data\Pagination;
use frontend\components\FrontendController;

class SiteController extends FrontendController
{
    public function actionIndex()
    {
        $this->findSeoAndSetMeta('index');
        $slider = Slider::find()->where(['status' => 1])->orderBy(['sort' => SORT_ASC])->all();
        $categories = ProductCategories::find()->where(['status' => 1])->andWhere(['show_on_home' => 1])->orderBy(['sort' => SORT_ASC])->all();
        $events = Events::find()->where(['status' => 1])->orderBy(['date' => SORT_DESC])->limit(5)->all();
        return $this->render('index',[
            'slider' => $slider,
            'categories' => $categories,
            'events' => $events,
        ]);
    }

    public function actionAbout()
    {
        $this->findSeoAndSetMeta('about');
        $about = Modules::find()->where(['id' => 1])->one();
        return $this->render('about',[
            'about' => $about,
        ]);
    }

    public function actionEvents($slug = NULL)
    {
        if ($slug) {
            $this->findSeoAndSetMeta($slug);
            if ($slug == 'concerts') {
                $query = Events::find()->where(['variants' => 0])->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
            }elseif ($slug == 'events') {
                $query = Events::find()->where(['variants' => 1])->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
            }elseif ($slug == 'kids') {
                $query = Events::find()->where(['variants' => 2])->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
            }elseif ($slug == 'news') {
                $query = Events::find()->where(['variants' => 3])->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
            }elseif ($slug == 'all-events') {
                $query = Events::find()->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
            }
        }else {
            $slug = 'all-events';
            $this->findSeoAndSetMeta($slug);
            $query = Events::find()->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $pages->pageSizeParam = false;
        $events = $query->offset($pages->offset)->limit($pages->limit)->all();
        $cities = Cities::find()->all();
        $restaurants = Restaurants::find()->where(['status' => 1])->all();
        return $this->render('events',[
            'events' => $events,
            'slug' => $slug,
            'pages' => $pages,
            'cities' => $cities,
            'restaurants' => $restaurants,
        ]);
    }

    public function actionEventList($rest_id)
    {
        $query = Events::find()->where(['restaurants_id' => $rest_id])->having(['status' => 1])->orderBy(['date' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 9]);
        $pages->pageSizeParam = false;
        $events = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->renderAjax('_event_list', [
            'events' => $events,
            'pages' => $pages,
        ]);
    }

    public function actionEvent($slug)
    {
        $event = Events::find()->where(['slug' => $slug])->andWhere(['status' => 1])->one();
        $this->setMeta($event->title, $event->metaDescription, $event->metaKeywords);
        return $this->render('event', [
            'event' => $event,
        ]);
    }

    public function actionContacts()
    {
        $contacts = Contacts::getDb()->cache(function () {
            return Contacts::find()->having(['status' => 1])->orderBy('sort')->all();
        }, Yii::$app->params['cacheTime']);
        $restaurants = Restaurants::find()->where(['status' => 1])->all();
        $cities = Cities::find()->where(['status' => 1])->all();
        return $this->render('contacts', [
            'contacts' => $contacts,
            'restaurants' => $restaurants,
            'cities' => $cities,
        ]);
    }

    public function actionAddReview() {
        $model = new Reviews();
        $model->scenario = 'create';
        $model->date = Yii::$app->formatter->asDatetime('now', 'dd.MM.yyyy H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->sendEmail();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Спасибо за Ваш отзыв!'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('reviewsForm', [
            'model' => $model,
        ]);
    }

    public function actionReserve()
    {
        $model = new ReserveForm();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('reserve_ajax', [
                'model' => $model,
            ]);
        } else {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->make()) {
                    if ($model->sendEmail()) {
                        Yii::$app->session->setFlash('success', Yii::t('app', "Ваш резерв принят"));
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', "Ошибка отправки e-mail"));
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', "Не могу сохранить резерв"));
                }
            }
            return $this->goHome();
        }
    }
    #######################################################################

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $model->password = '';

            return $this->renderAjax('login', [
                'model' => $model,
            ]);
        }
    }
}

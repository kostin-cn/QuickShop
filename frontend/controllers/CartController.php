<?php

namespace frontend\controllers;

use common\entities\Orders;
use frontend\forms\OrderForm;
use Yii;
use yii\base\Module;
use yii\web\NotFoundHttpException;
use common\models\Cart;
use common\models\CartItem;
use common\entities\Products;
use frontend\components\FrontendController;

class CartController extends FrontendController
{
    /* @var $cart Cart */
    private $cart;

    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cart = Yii::$container->get('common\models\Cart');
    }

    public function actionIndex()
    {
        if (!$this->cart->getItems()) {
            Yii::$app->session->setFlash('error', 'Корзина пуста.');
            return $this->redirect(['catalog/index']);
        }
        return $this->render('index', [
            'cart' => $this->cart,
        ]);
    }

    public function actionAdd()
    {
        $post = Yii::$app->request->post();
        $quantity = $post['quantity-input'] ?: $post['quantity'];
        $menu_item = Products::findOne($post['id']);
        $cart_item = new CartItem($menu_item, $quantity);
        $this->cart->add($cart_item);
        Yii::$app->session->setFlash('success', 'Добавлено в корзину.');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPlus($id)
    {
        if (!$product = Products::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $cart_item = new CartItem($product, 1);
        $this->cart->add($cart_item);
        $data = null;
        $qty = null;
        if ($item = $this->cart->getItem($id)) {
            $data = $item->getProduct();
            $qty = $item->getQuantity();
        }
        return $this->renderAjax('ajax_cart', [
            'data' => $data,
            'quantity' => $qty,
            'message' => 'Добавлено в корзину.'
        ]);
    }

    public function actionMinus($id)
    {
        if (!$product = Products::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        if (!$this->cart->getItem($id)) {
            return false;
        }
        $cart_item = new CartItem($product, -1);
        $this->cart->add($cart_item);
        if (!$item = $this->cart->getItem($id)) {
            return $this->renderAjax('ajax_cart', [
                'data' => $product,
                'quantity' => null,
                'message' => 'Удалено из корзины.'
            ]);
        }

        $data = $item->getProduct();
        $qty = $item->getQuantity();

        return $this->renderAjax('ajax_cart', [
            'data' => $data,
            'quantity' => $qty,
            'message' => 'Изменено количество.'
        ]);
    }


    public function actionUpdate($id, $inc)
    {
        /* @var $item CartItem */
        foreach ($this->cart->getItems() as $item) {
            if ($item->getId() == $id) {
                $quantity = $item->getQuantity() + $inc;
                if ($quantity < 1) {
                    $this->cart->remove($id);
                } else {
                    $this->cart->set($id, $quantity);
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemove($id)
    {
        $this->cart->remove($id);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClear()
    {
        $this->cart->clear();

        return $this->redirect(['catalog/index']);
    }

    public function actionReorder($id)
    {
        if (!$oldOrder = Orders::findOne($id)) {
            throw new NotFoundHttpException('Запрошенная вами страница не существует.');
        }
        $message = null;
        foreach ($oldOrder->orderItems as $item) {
            if ($product = Products::findOne(['id' => $item->product_id, 'category_status' => 1, 'status' => 1])) {
                $cartItem = new CartItem($product, $item->qty_item);
                $this->cart->add($cartItem);
            } else {
                $message .= $item->title . ' больше недоступен для заказа <br>';
            }
        }
        return $this->redirect(['checkout', 'message' => $message]);
    }

    public function actionCheckout($message = null)
    {
        if (!$this->cart->getItems()) {
            Yii::$app->session->setFlash('error', $message . 'Корзина пуста.');
            return $this->redirect(['catalog/index']);
        }
        if ($message) {
            Yii::$app->session->setFlash('error', $message);
        }
        $form = new OrderForm($this->cart);
        if (Yii::$app->user->isGuest) {
            $form->scenario = 'create';
        }
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($order = $form->create()) {
                try {
                    $form->mail($order);
                } catch (\RuntimeException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
                Yii::$app->session->setFlash('success', 'Спасибо за заказ, ждём Вас снова!<br>Номер заказа: '.$order->id.'<br>Сумма: '.$order->cost.' руб.<br>Наш менеджер свяжется с Вами в ближайшее время, для подтверждения заказа.');
                $this->cart->clear();
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('checkout', [
            'cart' => $this->cart,
            'model' => $form
        ]);
    }
}
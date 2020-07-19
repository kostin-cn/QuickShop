<?php

namespace frontend\forms;

use common\entities\OrderItems;
use common\entities\Orders;
use common\models\CartItem;
use common\entities\Cities;
use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;
use common\models\Cart;
use common\entities\User;

class OrderForm extends Model
{
    public $customer_name;
    public $customer_email;
    public $customer_phone;

    public $address;
    public $delivery_date;
    public $delivery_time;
    public $note;
    public $payMethod;
    public $whenReady;
    public $persons;
    public $change_from;

    public $verifyCode;
    public $data_collection_checkbox;

    private $cart;

    public function __construct(Cart $cart, $config = [])
    {
        parent::__construct($config);
        $this->cart = $cart;
        if (!Yii::$app->user->isGuest) {
            /* @var $user User */
            $user = Yii::$app->user->identity;

            $this->customer_name = $user->userProfile->getFullName();
            $this->customer_email = $user->email;
            $this->customer_phone = $user->phone;

            $this->address = $user->userAddresses[0]->value;
        }
    }

    public function rules()
    {
        return [
            [['address', 'note', 'customer_name'], 'filter', 'filter' => function ($value) {
                return HtmlPurifier::process($value);
            }],
            [['note', 'whenReady'], 'string'],
            [['payMethod', 'customer_name', 'customer_phone', 'address'], 'required'],
            [['customer_name', 'customer_email', 'address'], 'string', 'max' => 255],
            [['delivery_date', 'delivery_time'], 'string', 'max' => 50],
            [['customer_name', 'customer_email'], 'string', 'max' => 250],
            [['customer_phone'], 'string', 'max' => 50],
            [['change_from', 'persons'], 'integer'],

            ['verifyCode', 'captcha', 'captchaAction' => 'site/captcha', 'on' => 'create'],
            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Your Approve Required'),],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customer_name' => 'Имя',
            'customer_email' => 'E-mail',
            'customer_phone' => 'Телефон',
            'address' => 'Адрес доставки',
            'delivery_date' => 'Время доставки',
            'persons' => 'Количество персон',
            'change_from' => 'Сдача с',
            'note' => 'Комментарии',
            'payMethod' => 'Способ оплаты',
            'whenReady' => 'По готовности',
            'verifyCode' => 'Проверочный код',
            'data_collection_checkbox' => 'Согласие на обработку персональных данных',
        ];
    }

    public function create()
    {
        $order = new Orders();
        if (!Yii::$app->user->isGuest) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $order->user_id = $user->id;
            if (!$user->phone) {
                $user->phone = $this->customer_phone;
                $user->save();
            }
            if (!$user->userAddresses[0]->value) {
                $user->userAddresses[0]->value = $this->address;
                $user->userAddresses[0]->save();
            }
        }
        $order->name = $this->customer_name;
        $order->phone = $this->customer_phone;
        $order->email = $this->customer_email;

        $order->address = $this->address;

        if ($this->whenReady) {
//            $order->datetime = strtotime($this->delivery_date);
            $order->when_ready = 'По готовности';
        }
        if ($this->delivery_date) {
//            $order->datetime = strtotime($this->delivery_date);
            $order->datetime = $this->delivery_date;
        }

        $order->pay_method = $this->payMethod;
        $order->quantity = $this->cart->getAmount();
        $order->cost = $this->cart->getTotalCost();
        $order->change_from = $this->change_from;
        $order->persons = $this->persons;
        $order->note = $this->note;
        $order->cart_json = $this->cart->setArrayJson($this->cart);
        if ($order->save()) {
            $this->saveOrderItems($this->cart->getItems(), $order->id);
        }
        return $order;
    }

    protected function saveOrderItems($items, $order_id)
    {
        /* @var $item CartItem */
        foreach ($items as $id => $item) {
            $order_item = new OrderItems();
            $order_item->order_id = $order_id;
            $order_item->product_id = $item->getProductId();
            $order_item->title = $item->getProduct()->title;
            $order_item->qty_item = $item->quantity;
            $order_item->price_item = $item->getPrice();
            $order_item->save();
        }
    }

    public function mail($order)
    {
        $this->sendToAdmin($order);
        if ($this->customer_email) {
            $this->sendToCustomer($order);
        }
    }

    private $adminHtml;
    private $customerHtml;


    public function sendToAdmin($order)
    {
        /* @var $order Orders */
        $customCity = Cities::getDb()->cache(function () {
            return Cities::find()->where(['slug' => Yii::$app->params['customCity']])->having(['status' => 1])->one();
        }, Yii::$app->params['cacheTime']);
        $payMethods = Yii::$app->params['payMethods'];
        if ($order->when_ready){
            $date = 'По готовности';
        }else{
            $date = $order->datetime ? Yii::$app->formatter->asDatetime($order->datetime, 'dd-MM-yyyy H:mm') : null;
        }

        $this->adminHtml .= "<style>";
        $this->adminHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->adminHtml .= "</style>";
        $this->adminHtml .= "<table>";
        $this->adminHtml .= "<tr><td>Cпособ оплаты</td><td>: {$payMethods[$order->pay_method]}</td></tr>";
        $this->adminHtml .= "<tr><td>Время доставки</td><td>: {$date}</td></tr>";
        $this->adminHtml .= $order->persons ? "<tr><td>Количество персон</td><td>: {$order->persons}</td></tr>" : null;
        $this->adminHtml .= "<tr><td colspan='2' class='form-heading' ><h2>Клиент</h2></td><td></td></tr>";
        $this->adminHtml .= "<tr><td>Имя</td><td>: {$order->name}</td></tr>";
        $this->adminHtml .= $order->email ? "<tr><td>E-Mail</td><td>: {$order->email}</td></tr>" : null;
        $this->adminHtml .= "<tr><td>Телефон</td><td>: {$order->phone}</td></tr>";
        $this->adminHtml .= "<tr><td>Адрес</td><td>: {$order->address}</td></tr>";
        $this->adminHtml .= $order->note ? "<tr><td>Комментарии</td><td>: {$order->note}</td></tr>" : null;
        $this->adminHtml .= "</table>";

        $this->adminHtml .= "<h4>Список блюд:</h4>";
        $this->adminHtml .= "<table>";
        $this->adminHtml .= "<tr><td>id</td><td>Название</td><td> Количество</td><td> Цена</td></tr>";
        foreach ($order->orderItems as $item) {
            $this->adminHtml .= "<tr><td>{$item->id}</td><td>{$item->title}</td><td>{$item->qty_item}  шт.</td><td> {$item->price_item} руб.</td></tr>";
        }
        $this->adminHtml .= "<tr><td></td><td></td><td>Итого</td><td>: {$order->cost} руб.</td></tr>";
        $this->adminHtml .= $order->change_from ? "<tr><td></td><td></td><td>Сдача с</td><td>: {$order->change_from} руб.</td></tr>" : null;
        $this->adminHtml .= "</table>";

        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
//            ->setTo(Yii::$app->params['adminEmail'])
            ->setTo($customCity->feedback_email)
            ->setSubject('Заказ №'.$order->id.' от ' . $this->customer_name)
            ->setHtmlBody($this->adminHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }

    public function sendToCustomer($order)
    {
        /* @var $order Orders */
        $payMethods = Yii::$app->params['payMethods'];
        if ($order->when_ready){
            $date = 'По готовности';
        }else{
            $date = $order->datetime ? Yii::$app->formatter->asDatetime($order->datetime, 'dd-MM-yyyy H:mm') : null;
        }

        $this->customerHtml .= "<style>";
        $this->customerHtml .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->customerHtml .= "</style>";
        $this->customerHtml .= "<h2>Ваш заказ #{$order->id} принят</h2>";
        $this->customerHtml .= "<table>";
        $this->customerHtml .= "<tr><td>Имя</td><td>: {$order->name}</td></tr>";
        $this->customerHtml .= "<tr><td>Телефон</td><td>: {$order->phone}</td></tr>";
        $this->customerHtml .= "<tr><td>Адрес</td><td>: {$order->address}</td></tr>";
        $this->customerHtml .= $date ? "<tr><td>Время доставки</td><td>: {$date}</td></tr>" : null;
        $this->customerHtml .= "<tr><td>Cпособ оплаты</td><td>: {$payMethods[$order->pay_method]}</td></tr>";
        $this->customerHtml .= $order->persons ? "<tr><td>Количество персон</td><td>: {$order->persons}</td></tr>" : null;
        $this->customerHtml .= $order->note ? "<tr><td>Комментарии</td><td>: {$order->note}</td></tr>" : null;
        $this->customerHtml .= "</table>";

        $this->customerHtml .= "<h4>Список блюд:</h4>";
        $this->customerHtml .= "<table>";
        $this->customerHtml .= "<tr><td>Название</td><td> Количество</td><td> Цена</td></tr>";
        foreach ($order->orderItems as $item) {
            $this->customerHtml .= "<tr><td>{$item->title}</td><td>{$item->qty_item}  шт.</td><td> {$item->price_item} руб.</td></tr>";
        }
        $this->customerHtml .= "<tr><td></td><td>Итого</td><td>: {$order->cost} руб.</td></tr>";
        $this->customerHtml .= $order->change_from ? "<tr><td></td><td>Сдача с</td><td>: {$order->change_from} руб.</td></tr>" : null;
        $this->customerHtml .= "</table>";

        $sent = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($this->customer_email)
            ->setSubject('Подтверждение заказа №'.$order->id.' с сайта ' . Yii::$app->name)
            ->setHtmlBody($this->customerHtml)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Ошибка отправки E-mail.');
        }
    }
}
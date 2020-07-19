<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\entities\Reserves;
use common\entities\Restaurants;
use yii\helpers\ArrayHelper;

class ReserveForm extends Model
{
    public $restaurant_id;
    public $name;
    public $email;
    public $phone;
    public $date;
    public $time_from;
    public $time_till;
    public $qty;
    public $notes;
    public $data_collection_checkbox;

    public function rules()
    {
        return [
            [['name', 'date', 'qty', 'email', 'phone', 'time_from'], 'required'],
            [['data_collection_checkbox'], 'required', 'requiredValue' => 1, 'message' => Yii::t('app', 'Your Approve Required'),],
            [['qty', 'status', 'restaurant_id'], 'integer'],
            [['email'], 'email'],
            [['name'], 'string', 'max' => 255],
            [['date'], 'string', 'max' => 50],
            [['time_from', 'time_till'], 'date', 'format' => 'php:H:i'],
            [['phone'], 'string', 'max' => 20],
            [['notes'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'E-mail'),
            'phone' => Yii::t('app', 'Phone'),
            'date' => Yii::t('app', 'Date'),
            'time_from' => Yii::t('app', 'Time from'),
            'time_till' => Yii::t('app', 'Time till'),
            'qty' => Yii::t('app', 'Persons'),
            'notes' => Yii::t('app', 'Notes'),
        ];
    }

    public function restArr()
    {
        return ArrayHelper::map(Restaurants::find()->having(['status' => 1])->all(), 'id', 'title');
    }

    public function make()
    {
        $model = new Reserves();
        $model->restaurant_id = $this->restaurant_id;
        $model->name = $this->name;
        $model->email = $this->email;
        $model->phone = $this->phone;
        $model->date = strtotime($this->date);
        $model->time = $this->time_from . "-" . $this->time_till ?: '??:??';
        $model->qty = $this->qty;
        $model->notes = $this->notes;
        $model->language = Yii::$app->language;
        return $model->save();
    }

    private $html;

    public function sendEmail()
    {
        $time = $this->time_from . "-" . $this->time_till ?: '??:??';
        $this->html .= "<style>";
        $this->html .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->html .= "</style>";
        $this->html .= "<table>";
		$this->html .= "<tr><td><h2>Ресторан</h2></td><td>: {$this->restArr()[$this->restaurant_id]}</td></tr>";
        $this->html .= "<tr><td colspan='2' class='form-heading' ><h2>Клиент</h2></td><td></td></tr>";
        $this->html .= "<tr><td>Имя</td><td>: {$this->name}</td></tr>";
        $this->html .= "<tr><td>E-Mail</td><td>: {$this->email}</td></tr>";
        $this->html .= "<tr><td>Телефон</td><td>: {$this->phone}</td></tr>";
        $this->html .= "<tr><td>Дата</td><td>: {$this->date}</td></tr>";
        $this->html .= "<tr><td>Время</td><td>: {$time} </td></tr>";
        $this->html .= "<tr><td>Персон</td><td>: {$this->qty} </td></tr>";
        $this->html .= "<tr><td>Пожелания</td><td>: {$this->notes}</td></tr>";
        $this->html .= "</table>";

        $messages = [];
        //foreach (Yii::$app->params['adminEmails'] as $adminEmail) {
            $messages[] = Yii::$app->mailer->compose()
                ->setTo(Yii::$app->params['adminEmail'])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setSubject('Клиент ' . $this->name)
                ->setHtmlBody($this->html);
        //}

        return Yii::$app->mailer->sendMultiple($messages);
    }
}
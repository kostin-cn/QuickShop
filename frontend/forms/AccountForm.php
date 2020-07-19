<?php

namespace frontend\forms;

use common\components\CompositeForm;
use common\entities\User;
use common\entities\UserAddress;

/**
 * @property UserAddress $addresses
 */
class AccountForm extends CompositeForm
{
    public $username;
    public $phone;
    public $email;
    public $birthday;
    public $get_email;
    public $get_sms;

    private $_phone;
    private $_email;
    private $_user;

    public function __construct(array $config = [])
    {
        /* @var User $user */
        parent::__construct($config);
        $user = \Yii::$app->user->identity;
        $this->_user = $user;
        $this->_email = $user->email;
        $this->_phone = $user->phone;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->birthday = $user->birthday;
        $this->get_email = $user->get_email;
        $this->get_sms = $user->get_sms;
        $this->addresses = $user->userAddresses;
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => $this->_email ? ['not like', 'email', $this->_email] : null, 'message' => 'Этот e-mail адрес уже занят.'],

            ['birthday', 'string', 'max' => 11],
            [['get_email', 'get_sms'], 'integer'],
            ['phone', 'string', 'max' => 255],
            ['phone', 'unique', 'targetClass' => User::class, 'filter' => $this->_phone ? ['not like', 'phone', $this->_phone] : null, 'message' => 'Этот телефон уже занят.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Ф.И.О.',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'birthday' => 'Дата рождения',
            'get_email' => 'Я хочу получать e-mail уведомления об акциях и событиях в Merzen',
            'get_sms' => 'Я хочу получать sms уведомления об акциях и событиях в Merzen',
        ];
    }

    public function editAccount(): User
    {
        if (!$this->validate()) {
            return null;
        }
        /* @var User $user */
        $user = \Yii::$app->user->identity;

        $user->edit($this->username, $this->phone, $this->email, $this->birthday, $this->get_email, $this->get_sms);

        foreach ($this->addresses as $address) {
            $address->save();
        }
        return $user->save() ? $user : $this->_user;
    }

    public function internalForms(): array
    {
        return ['addresses'];
    }
}
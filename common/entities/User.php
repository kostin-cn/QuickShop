<?php

namespace common\entities;

use yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $phone
 * @property string $birthday
 * @property string $role
 * @property string $auth_key
 * @property integer $status
 * @property integer $get_email
 * @property integer $get_sms
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property UserProfile $userProfile
 * @property UserAddress[] $userAddresses
 * @property Orders[] $orders
 * @property Orders[] $activeOrders
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ROLE_CUSTOMER = 'user';
    const ROLE_MANAGER = 'manager';
    const ROLE_ADMIN = 'admin';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            ['role', 'default', 'value' => 'user'],
            ['birthday', 'string', 'max' => 11],
            [['get_email', 'get_sms'], 'integer'],
            ['role', 'in', 'range' => [self::ROLE_CUSTOMER, self::ROLE_MANAGER, self::ROLE_ADMIN]],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Ф.И.О.',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'created_at' => 'Зарегистрирован',
            'birthday' => 'Дата рождения',
            'get_email' => 'Я хочу получать e-mail уведомления об акциях и событиях в Merzen',
            'get_sms' => 'Я хочу получать sms уведомления об акциях и событиях в Merzen',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    public function getUserAddresses()
    {
        return $this->hasMany(UserAddress::class, ['user_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    public function getActiveOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id'])->having(['user_status' => 1]);
    }

    public static function isUserAdmin($email)
    {
        return static::findOne(['email' => $email, 'role' => self::ROLE_ADMIN]) ? true : false;
    }

    public static function isUserCustomer($email)
    {
        return static::findOne(['email' => $email, 'role' => self::ROLE_CUSTOMER]) ? true : false;
    }

    public static function isUserManager($email)
    {
        return static::findOne(['email' => $email, 'role' => self::ROLE_MANAGER]) ? true : false;
    }

    public function getPublicIdentity()
    {
        if ($this->userProfile && $this->userProfile->getFullname()) {
            return $this->userProfile->getFullname();
        }
        if ($this->username) {
            return $this->username;
        }
        return $this->email;
    }

    public static function signup($username, $email, $password)
    {
        $user = new static();
        $user->email = $email;
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->save();
        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->save();
        $address = new UserAddress();
        $address->scenario = 'machine';
        $address->user_id = $user->id;
        $address->save();
        return $user->save() ? $user : null;
    }

    public function edit($username, $phone, $email, $birthday, $get_email, $get_sms)
    {
        $this->username = $username;
        $this->phone = $phone;
        $this->email = $email;
        $this->birthday = $birthday;
        $this->get_email = $get_email;
        $this->get_sms = $get_sms;
        return $this->save();
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }
}

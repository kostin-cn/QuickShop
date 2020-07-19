<?php

namespace common\entities;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\HtmlPurifier;
use common\entities\Cities;

/**
 * This is the model class for table "{{%reviews}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $date
 * @property integer $rate
 * @property integer $status
 */
class Reviews extends ActiveRecord
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if (!Yii::$app->user->isGuest){
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $this->name = $user->username;
        }
    }

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reviews}}';
    }

    public function behaviors( ) {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'name'], 'filter', 'filter' => function ($value) {
                return HtmlPurifier::process($value);
            }],
            [['description'], 'string'],
            [['name', 'rate', 'description'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['rate'], 'integer', 'max' => 11],
            [['status'], 'integer', 'max' => 1],
            ['verifyCode', 'captcha', 'captchaAction'=>'site/captcha', 'on' => 'create'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'description' => 'Отзыв',
            'rate' => 'Оценка',
            'date' => 'Дата',
            'status' => 'Status',
            'verifyCode' => 'Проверочный код',
        ];
    }

    private $html;

    public function sendEmail()
    {
        $customCity = Cities::getDb()->cache(function () {
            return Cities::find()->where(['slug' => Yii::$app->params['customCity']])->having(['status' => 1])->one();
        }, Yii::$app->params['cacheTime']);
        $rating = $this->rate +1;
        $this->html .= "<style>";
        $this->html .= ".h2{ font-size:2em; font-weight:lighter; text-transform:uppercase;}";
        $this->html .= "</style>";
        $this->html .= "<table>";
        $this->html .= "<tr><td colspan='2' class='form-heading' ><h2>Отзыв</h2></td><td></td></tr>";
        $this->html .= "<tr><td>Имя</td><td>: {$this->name}</td></tr>";
        $this->html .= "<tr><td>Оценка</td><td>: {$rating}</td></tr>";
        $this->html .= "<tr><td>Текст отзыва</td><td>: {$this->description}</td></tr>";
        $this->html .= "</table>";

        $message = Yii::$app->mailer->compose()
            ->setTo($customCity->feedback_email)
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setSubject('Сообщение от ' . $this->name)
            ->setHtmlBody($this->html);

        return Yii::$app->mailer->send($message);
    }
}
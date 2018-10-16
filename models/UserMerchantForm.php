<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;


class UserMerchantForm extends ActiveRecord
{

    public $USER_ID;
    public $EMAIL;
    public $PASSWORD;
    public $MOBILE;
    public $USER_TYPE;
    public $PARTNER_ID;
    public $FIRST_NAME;
    public $LAST_NAME;
    public $USER_STATUS;
    public $REPEAT_PASSWORD;
    public $INIT_PASSWORD;
    public $UP_PASSWORD;
    public $UP_REPEAT_PASSWORD;
    public $assignee_name;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EMAIL', 'USER_TYPE', 'FIRST_NAME', 'LAST_NAME', 'USER_STATUS','MOBILE'], 'required'],
            [['USER_TYPE', 'USER_STATUS'], 'string'],
            [['PARTNER_ID'], 'integer'],
            [['EMAIL'], 'email'],
            //['EMAIL', 'validateEmail'],
            //[['MOBILE'], 'number', 'min'=>10,'max'=>10, 'message' => 'Enter valid Mobile Number.'],
            [['EMAIL'], 'unique'],
            ['MOBILE', 'match', 'pattern' => '/^[0-9]{10,10}$/', 'message'=>'Enter valid Mobile Number.'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'required', 'on'=>'insert'],
            [['PASSWORD', 'REPEAT_PASSWORD'], 'string', 'min'=>5, 'max'=>60],
            [['PASSWORD'], 'compare', 'compareAttribute'=>'REPEAT_PASSWORD',  'on'=>'insert'],
            [['EMAIL', 'FIRST_NAME', 'LAST_NAME'], 'string', 'max' => 50],
            [['PASSWORD'], 'string', 'max' => 200],
            [['USER_STATUS'], 'string', 'max' => 1]
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => 'User',
            'EMAIL' => 'Email',
            'MOBILE' => 'Mobile',
            'PASSWORD' => 'Password',
            'UP_PASSWORD' => 'Password',
            'UP_REPEAT_PASSWORD' => 'Repeat Password',
            'USER_TYPE' => 'User  Type',
            'PARTNER_ID' => 'Vendor  ID',
            'FIRST_NAME' => 'First  Name',
            'LAST_NAME' => 'Last  Name',
            'USER_STATUS' => 'User  Status',
            'CREATED_ON' => 'Created  On',
            'UPDATED_ON' => 'Updated  On',
        ];
    }

    public function validateEmail()
    {

        if (!empty($user)) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }
    public function beforeValidate() {
        foreach($this->attributes as $key => $value)  {
            if(is_string($value))   {
                $this->$key = trim($value);
            }
        }
        // var_dump($this->DB_NAME); exit;

        return parent::beforeValidate();
    }






    public function beforeSave($insert)
    {
        if(!empty($this->PASSWORD) && ($this->PASSWORD == $this->REPEAT_PASSWORD))  {
            $this->setPassword($this->PASSWORD);
            $this->INIT_PASSWORD = $this->PASSWORD;
        }   else    {
            $this->PASSWORD = $this->INIT_PASSWORD;
        }
        if($insert)  {
            $this->CREATED_ON = time();
        }   else    {

            $this->UPDATED_ON = time();
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        //reset the password to null because we don't want the hash to be shown.
        $this->INIT_PASSWORD = $this->PASSWORD;
        $this->FIRST_NAME = $this->FIRST_NAME;
        $this->LAST_NAME = $this->LAST_NAME;
        $this->PASSWORD = $this->PASSWORD;
        $this->EMAIL = $this->EMAIL;
        $this->USER_STATUS = $this->USER_STATUS;
        $this->USER_TYPE = $this->USER_TYPE;
        $this->MOBILE = $this->MOBILE;
        //$this->PASSWORD = null;
        $this->assignee_name = $this->FIRST_NAME . ' ' . $this->LAST_NAME;

       /* if(!Yii::$app->user->isGuest && (Yii::$app->user->identity->USER_TYPE == 'admin' || Yii::$app->user->identity->USER_TYPE == 'cro'))    {
            if(!empty($this->hotel))    {
                $this->assignee_name = $this->hotel->HOTEL_NAME . ' - '.$this->FIRST_NAME . ' ' . $this->LAST_NAME;
            }
        }*/


    }
}

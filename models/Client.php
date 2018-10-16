<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_client".
 *
 * @property integer $CLIENT_ID
 * @property integer $ORDER_ID
 * @property string $EMAIL
 * @property double $PHONE
 * @property string $FIRST_NAME
 * @property string $LAST_NAME
 * @property string $COMPANY_NAME
 * @property string $ADDRESS
 * @property string $CITY
 * @property string $STATE
 * @property string $COUNTRY
 * @property string $PINCODE
 * @property double $CREATED_ON
 */
class Client extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_client';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
       /* $dbconn =Yii::$app->controller->db_name;
        if(!empty($dbconn)) {
            return Yii::$app->get($dbconn);
        }*/
        return Yii::$app->get(!empty(Yii::$app->controller->DB_name)?Yii::$app->controller->DB_name:'db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EMAIL', 'PHONE', 'FIRST_NAME', 'LAST_NAME' ], 'required'],
            // [['ORDER_ID', 'EMAIL', 'PHONE', 'FIRST_NAME', 'LAST_NAME', 'COMPANY_NAME', 'ADDRESS', 'CITY', 'STATE', 'COUNTRY', 'PINCODE', ], 'required'],
            [['ORDER_ID', 'PHONE'], 'number'],
            [['ORDER_ID','CREATED_ON'], 'number'],
//            [['PHONE'], 'number', 'min'=>1],
//            [['PHONE'], 'match', 'pattern' => '/^[0-9]{10,10}$/', 'message'=>'Enter valid Mobile Number.'],
            [['PHONE'], 'number', 'min' => 1, 'max' => 9999999999, 'message'=>'Number is invalid.', 'tooSmall'=>'Number is invalid.', 'tooBig'=>'Number is invalid.'],
            [['EMAIL'],'email'],
            [['EMAIL', 'COMPANY_NAME'], 'string', 'max' => 50],
            //[['FIRST_NAME', 'LAST_NAME', ], 'string', 'min'=>'1', 'max'=>50],
            //[['FIRST_NAME', 'LAST_NAME', ], 'match', 'pattern' => '/^[A-Za-z\d\s]{1,50}$/'],
            [['CITY', 'STATE', 'COUNTRY'], 'string', 'max' => 30],
            [['CITY', 'STATE', 'COUNTRY'], 'safe'],
            [['ADDRESS'], 'string', 'max' => 250],
            [['PINCODE'], 'string', 'max' => 6],
            [['FIRST_NAME'], 'match', 'pattern' => '/^([a-zA-Z\s]+)$/', 'message'=>'First Name should contain alphabet only.'],
            [['LAST_NAME'], 'match', 'pattern' => '/^([a-zA-Z\s]+)$/', 'message'=>'Last Name should contain alphabet only.'],
           // [['ORDER_ID', 'PHONE' ], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CLIENT_ID' => 'Client  ID',
            'ORDER_ID' => 'Order  ID',
            'EMAIL' => 'Email',
            'PHONE' => 'Phone',
            'FIRST_NAME' => 'First  Name',
            'LAST_NAME' => 'Last  Name',
            'COMPANY_NAME' => 'Company  Name',
            'ADDRESS' => 'Address',
            'CITY' => 'City',
            'STATE' => 'State',
            'COUNTRY' => 'Country',
            'PINCODE' => 'Pincode',
            'CREATED_ON' => 'Created  On',
        ];
    }
    public function beforeSave($insert)
    {


        if($insert)  {

            $this->CREATED_ON = time();

        }   else    {
            $this->UPDATED_ON = time();
        }

        return parent::beforeSave($insert);
    }
}

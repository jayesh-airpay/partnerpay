<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tbl_merchant_master".
 *
 * @property integer $MERCHANT_ID
 * @property string $MERCHANT_NAME
 * @property string $DOMAIN_NAME
 * @property string $DB_NAME
 * @property string $MERCHANT_LOGO
 * @property string $BANK_LOGO
 * @property string $AIRPAY_MERCHANT_KEY
 * @property string $AIRPAY_MERCHANT_USERNAME
 * @property string $AIRPAY_MERCHANT_PASSWORD
 * @property string $AIRPAY_MERCHANT_SECRETE_KEY
 * @property string $MERCHANT_ADDRESS
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 * @property string $MOBILE
 * @property string $CREATE_QR

 */
class MerchantMaster extends \yii\db\ActiveRecord
{
    public $REPEAT_PASSWORD;
    public $INIT_PASSWORD;
    public $LOGO;
	public $B_LOGO;
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_merchant_master';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INSERT] = array_merge($scenarios['default'] , ['LOGO','B_LOGO']);
        $scenarios[self::SCENARIO_UPDATE] = array_merge($scenarios['default'] , ['LOGO','B_LOGO']);
        return $scenarios;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MERCHANT_NAME', 'DOMAIN_NAME', 'MERCHANT_ADDRESS','MERCHANT_STATUS'], 'required'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
            [['DOMAIN_NAME'], 'unique'],
            [['DOMAIN_NAME'], 'match', 'pattern' => '/^([-0-9a-zA-Z.]+)$/', 'message'=>'Domain Name should contain alphanumeric, dot & dash only.'],
            //[['MERCHANT_NAME'], 'match', 'pattern' => '/^([a-zA-Z\s]+)$/', 'message'=>'Merchant Name should contain letters only.'],
            [['MERCHANT_NAME', 'DOMAIN_NAME'], 'string', 'max' => 255],
            [['DB_NAME'], 'string', 'max' => 200]    ,
//            [['LOGO'], 'required', 'on'=>'insert'],
            [['LOGO', 'B_LOGO'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, gif, jpeg, png', 'wrongExtension' => 'Not a valid image.', 'on' => self::SCENARIO_INSERT],
            [['LOGO', 'B_LOGO'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, gif, jpeg, png', 'wrongExtension' => 'Not a valid image.', 'on' => self::SCENARIO_UPDATE],
            [['MERCHANT_LOGO','MERCHANT_ADDRESS', 'AIRPAY_MERCHANT_KEY', 'AIRPAY_MERCHANT_USERNAME', 'AIRPAY_MERCHANT_PASSWORD', 'AIRPAY_MERCHANT_SECRETE_KEY', 'CREATE_QR'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MERCHANT_ID' => 'Merchant',
            'MERCHANT_NAME' => 'Merchant Name',
            'DOMAIN_NAME' => 'Domain Name',
            'DB_NAME' => 'Database Name',
            //'AIRPAY_MERCHANT_KEY' => 'Airpay  Merchant ID',
            //'AIRPAY_MERCHANT_USERNAME' => 'Airpay  Merchant  Username',
            //'AIRPAY_MERCHANT_PASSWORD' => 'Airpay  Merchant  Password',
            //'AIRPAY_MERCHANT_SECRETE_KEY' => 'Airpay  Merchant  Secret  Key',
            'MERCHANT_ADDRESS' => 'Merchant Address',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
            'MOBILE' => 'Mobile',
            'MERCHANT_LOGO' => 'Merchant Logo',
            'BANK_LOGO' => 'Bank Logo',
            'LOGO' => '',
            'MERCHANT_STATUS' => 'Merchant Status',
            'CREATE_QR' => 'PO Mandatory',
        ];
    }


    public function beforeValidate() {

            $new_data = preg_replace('/[^a-zA-Z0-9_.]/', '_', $this->MERCHANT_NAME);
            $new_data = preg_replace('/_+/', '_', $new_data);
            $this->DB_NAME = $new_data;
            $new_data = trim($new_data, '_');
            $this->DB_NAME = 'partnerpay_'. $this->DB_NAME;
            if(strlen($this->DB_NAME) > 64) {
                $this->DB_NAME =substr($this->DB_NAME, 0, 64);
            }

            // something happens here
            foreach($this->attributes as $key => $value)  {
                if(is_string($value))   {
                    $this->$key = trim($value);
                }
            }
			// var_dump($this->DB_NAME); exit;

            return parent::beforeValidate();

    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        //$this->MERCHANT_LOGO ='';
        if(!empty($this->PASSWORD) && ($this->PASSWORD == $this->REPEAT_PASSWORD))  {
            $this->setPassword($this->PASSWORD);
            $this->INIT_PASSWORD = $this->PASSWORD;
        }   else    {
            $this->PASSWORD = $this->INIT_PASSWORD;
        }
        $this->LOGO = UploadedFile::getInstance($this,'LOGO');
        if(!empty($this->LOGO))  {
            $extension = explode('.',$this->LOGO->name);
            $ext = end($extension);
            $filename = md5(time().$this->LOGO->name).'.'.$ext;
            //var_dump(Yii::$app->basePath.'/web/uploads/logo/'.$filename); exit;
            if($this->LOGO->saveAs(Yii::$app->basePath.'/web/uploads/logo/'.$filename)) {
                if(!empty($this->MERCHANT_LOGO))  {
                    unlink(Yii::$app->basePath.'/web/uploads/logo/'.$this->MERCHANT_LOGO);
                }
                $this->MERCHANT_LOGO = $filename;
            }
        }
    	
    	$this->B_LOGO = UploadedFile::getInstance($this,'B_LOGO');
        if(!empty($this->B_LOGO))  {
            $extension = explode('.',$this->B_LOGO->name);
            $ext = end($extension);
            $filename = md5(time().$this->B_LOGO->name).'.'.$ext;
            //var_dump(Yii::$app->basePath.'/web/uploads/logo/'.$filename); exit;
            if($this->B_LOGO->saveAs(Yii::$app->basePath.'/web/uploads/bank_logo/'.$filename)) {
                if(!empty($this->BANK_LOGO))  {
                    unlink(Yii::$app->basePath.'/web/uploads/bank_logo/'.$this->BANK_LOGO);
                }
                $this->BANK_LOGO = $filename;
            }
        }

		if($this->CREATE_QR == '0') {
			$this->CREATE_QR = 'N';
		}
		else {
			$this->CREATE_QR = 'Y';
		}
		
        if($insert)  {
            $this->CREATED_ON = time();
        	$this->MERCHANT_STATUS = "E";
            //$dbName = $this->checkDatabaseExist($this->DB_NAME);

            if(!empty($dbName)){
                $this->DB_NAME = $dbName;
            }
            //$createval = $this->createDatabase($this->DB_NAME);
            //$createtbl = $this->crateDatabaseTables($this->DB_NAME);
        }   else    {
            $this->UPDATED_ON = time();
        }

       return parent::beforeSave($insert);
    }

    public function setPassword($password)
    {
        $this->PASSWORD = Yii::$app->security->generatePasswordHash($password);
    }


    protected function checkDatabaseExist($dbname)
    {
        $dbName = '';
        $x = 0;

        while(empty($dbName)) {
            if($x > 0){
                $dbname = $dbname."_".$x;
            }
            $sql = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$dbname.'"';
            $command2 = Yii::$app->db->createCommand($sql)->queryAll();
            if(empty($command2[0]['SCHEMA_NAME'])){
                $dbName = $dbname;
            }
            $x++;
        }
        //var_dump($dbName); exit;
        return $dbName;

    }


    protected function createDatabase($dbname)
    {
        // $database_query = 'CREATE Database ' . $dbname;
        //$database_query1 = 'CREATE Database partnerpay_'. $dbname;
        $database_query1 = 'CREATE Database ' . $dbname;
        $command1 = Yii::$app->db->createCommand($database_query1);
        $s = $command1->execute();

    }

    protected function crateDatabaseTables($dbname)
    {
        $database_query = '';
        $database_query .= " CREATE TABLE IF NOT EXISTS ".$dbname.".`tbl_client` (
          `CLIENT_ID` int(11) NOT NULL AUTO_INCREMENT,
          `ORDER_ID` int(11) NOT NULL,
          `EMAIL` varchar(50) NOT NULL,
          `PHONE` double(12,0) NOT NULL,
          `FIRST_NAME` varchar(30) NOT NULL,
          `LAST_NAME` varchar(30) NOT NULL,
          `COMPANY_NAME` varchar(50) NOT NULL,
          `ADDRESS` varchar(250) NOT NULL,
          `CITY` varchar(30) NOT NULL,
          `STATE` varchar(30) NOT NULL,
          `COUNTRY` varchar(30) NOT NULL,
          `PINCODE` varchar(6) NOT NULL,
          `CREATED_ON` double(20,0) NOT NULL,
          PRIMARY KEY (CLIENT_ID)
        );";

        $database_query .=  " CREATE TABLE IF NOT EXISTS ".$dbname.".tbl_hotel_master (
          `HOTEL_ID` int(11) NOT NULL AUTO_INCREMENT,
          `HOTEL_NAME` varchar(50) NOT NULL,
          `HOTEL_LOCATION` varchar(30) NOT NULL,
          `AIRPAY_MERCHANT_ID` int(11) NOT NULL,
          `AIRPAY_USERNAME` varchar(50) NOT NULL,
          `AIRPAY_PASSWORD` varchar(50) NOT NULL,
          `AIRPAY_SECRET_KEY` varchar(100) NOT NULL,
          `VENDOR_LOGO` varchar(200) NOT NULL,
          `EMAIL_FOOTER` text NOT NULL,
          `HOTEL_STATUS` enum('E','D') NOT NULL,
          `SERVICE_TAX` double(20,0) NOT NULL,
          `VAT_TAX` double(20,0) NOT NULL,
          `SURCHARGES` double(20,0) NOT NULL,
          `CREATED_ON` double(20,0) NOT NULL,
          `UPDATED_ON` double(20,0) NOT NULL,
          PRIMARY KEY (HOTEL_ID)
        );";
        $database_query .= " CREATE TABLE IF NOT EXISTS ".$dbname.".tbl_invoice (
          `INVOICE_ID` int(11) NOT NULL AUTO_INCREMENT,
          `HOTEL_ID` int(11) NOT NULL,
          `REF_ID` varchar(20) NOT NULL,
          `CREATED_BY` int(11) NOT NULL,
          `IS_CORPORATE` enum('Y','N') NOT NULL DEFAULT 'N',
          `ASSIGN_TO` int(11) NOT NULL,
          `COMPANY_NAME` varchar(100) NOT NULL,
          `CLIENT_EMAIL` varchar(50) NOT NULL,
          `CLIENT_MOBILE` double(20,0) NOT NULL,
          `MAIL_SENT` enum('Y','N') NOT NULL DEFAULT 'N',
          `AMOUNT` double(10,2) NOT NULL,
          `PAID` double(10,2) NOT NULL DEFAULT '0.00',
          `BALANCE` double(10,2) NOT NULL,
          `INVOICE_STATUS` smallint(1) NOT NULL DEFAULT '0' COMMENT '0:PENDING; 1:PAID',
          `ATTACHMENT` varchar(36) DEFAULT NULL,
          `EXPIRY_DATE` double(20,0) NOT NULL,
          `INVOICE_EMAIL_TEMPLATE` text NOT NULL,
          `INVOICE_SMS_TEMPLATE` text NOT NULL,
          `REMINDER_INVOICE_EMAIL_TEMPLATE` text NOT NULL,
          `REMINDER_INVOICE_SMS_TEMPLATE`  varchar(255) NOT NULL,
          `CREATED_ON` double(20,0) NOT NULL,
          `UPDATED_ON` double(20,0) NOT NULL,
          PRIMARY KEY (INVOICE_ID)
        );";

        $database_query .= " CREATE TABLE IF NOT EXISTS ".$dbname.".`tbl_manual_order` (
          `MANUAL_ORDER_ID` int(11) NOT NULL AUTO_INCREMENT,
          `ORDER_ID` int(11) NOT NULL,
          `USER_ID` int(11) NOT NULL,
           PRIMARY KEY (MANUAL_ORDER_ID)
        );";

        $database_query .= " CREATE TABLE IF NOT EXISTS ".$dbname.".`tbl_order` (
          `ORDER_ID` int(11) NOT NULL AUTO_INCREMENT,
          `INVOICE_ID` int(11) NOT NULL,
          `PAYMENT_METHOD` smallint(1) NOT NULL DEFAULT '1' COMMENT '1: airpay; 2: cash; 3: check; 4: neft',
          `RECEIVED_AMOUNT` double(10,2) NOT NULL,
          `PAYMENT_STATUS` tinyint(1) NOT NULL COMMENT '0:PENDING; 1:SUCCESS; 2:ERROR',
          `TRANSACTION_ID` int(11) NOT NULL,
          `TRANSACTION_STATUS` int(4) NOT NULL,
          `TRANSACTION_MESSAGE` varchar(50) NOT NULL,
          `CREATED_ON` double(20,0) NOT NULL,
           PRIMARY KEY (ORDER_ID)
        );";

        $database_query .= " CREATE TABLE IF NOT EXISTS ".$dbname.".`tbl_user_merchant` (
          `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
          `EMAIL` varchar(50) NOT NULL,
          `PASSWORD` varchar(200) NOT NULL,
          `USER_TYPE` enum('merchant','hotel','sale','cro') NOT NULL,
          `HOTEL_ID` int(11) NOT NULL DEFAULT '0',
          `FIRST_NAME` varchar(50) NOT NULL,
          `LAST_NAME` varchar(50) NOT NULL,
          `ACCESS_TOKEN` varchar(200) NOT NULL,
          `AUTH_KEY` varchar(200) NOT NULL,
          `USER_STATUS` enum('E','D') NOT NULL,
          `CREATED_ON` double(20,0) NOT NULL,
          `UPDATED_ON` double(20,0) NOT NULL DEFAULT '0',
           PRIMARY KEY (USER_ID)
        );";

        //var_dump($database_query); exit;
        $command = Yii::$app->db->createCommand($database_query);
        $s = $command->execute();
    }

/* public function active($merchant_id = null)

    {
        //$query->where(['MERCHANT_ID' => $merchant_id]);
        $this->where(['MERCHANT_ID' => $merchant_id]);
        return $this;
    }*/

    public static function find()
    {
        if(!Yii::$app->user->isGuest)  {

            if(Yii::$app->getUser()->identity->USER_TYPE != 'admin')    {
                return parent::find()->andWhere([
                    'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                ]);
            }
        }
        return parent::find();
    }
}
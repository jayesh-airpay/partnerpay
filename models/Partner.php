<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table  "{{%partner_master}}".
 *
 * @property integer $PARTNER_ID
 * @property string $PARTNER_NAME
 * @property string $PARTNER_LOCATION
 * @property integer $AIRPAY_MERCHANT_ID
 * @property string $AIRPAY_USERNAME
 * @property string $AIRPAY_PASSWORD
 * @property string $AIRPAY_SECRET_KEY
 * @property string $EMAIL_FOOTER
 * @property string $PARTNER_STATUS
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 * @property string $SERVICE_TAX
 * @property string $VAT_TAX
 * @property double $SURCHARGES
 * @property string $VENDOR_LOGO
 * @property integer $MERCHANT_ID
 * @property string $MOBILE
 * @property string $INVOICE_EMAIL_TEMPLATE
 * @property string $INVOICE_SMS_TEMPLATE
 * @property string $REMINDER_INVOICE_EMAIL_TEMPLATE
 * @property string $REMINDER_INVOICE_SMS_TEMPLATE
 *
 * @property Invoice[] $INVOICE
 * @property MerchantMaster $Merchant
  * @property UserMaster $Approver

 */
class Partner extends \yii\db\ActiveRecord
{
    public $LOGO;
	public $PAN_LOGO;
    public $CATEGORIES;
    const SCENARIO_INSERT = 'insert';
	 const SCENARIO_IMPORT = 'import';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_partner_master';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INSERT]= $scenarios['default'] + ['LOGO', 'PAN_LOGO'];
    	
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LOGO'], 'required', 'on'=>'insert'],
            [['LOGO'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg', 'jpeg', 'gif', 'png'], 'wrongExtension' => 'Not a valid image.'],
            [['PAN_LOGO'], 'file', 'skipOnEmpty' => true, 'extensions' => ['jpg','jpeg', 'gif', 'png'], 'wrongExtension' => 'Not a valid image.'],
            [['PARTNER_NAME', 'PARTNER_LOCATION', 'MOBILE','MERCHANT_ID','EMAIL_ID','CORPORATE_PAN_CARD_NUMBER', 'VENDOR_REFERENCE_ID','GSTNUM'], 'required'],
            [['EMAIL_ID'],'email'],
            ['EMAIL_ID', 'unique', 'targetAttribute' => 'EMAIL','targetClass' => UserUnique::className(), 'on'=>'insert'],
            ['GSTNUM', 'unique', 'targetAttribute' => 'GSTNUM', 'on'=>'insert'],
            [['AIRPAY_MERCHANT_ID', 'AIRPAY_USERNAME','AIRPAY_PASSWORD', 'AIRPAY_SECRET_KEY'], 'required', 'when' => function($model) {
                return (Yii::$app->getUser()->identity->USER_TYPE == 'admin');
            }],
            [['AIRPAY_MERCHANT_ID','ACCOUNT_NUMBER'], 'integer'],
            [['VENDOR_LOGO','PAN_CARD_LOGO'], 'string', 'max' => 100],
//            [['SERVICE_TAX'], 'number', 'min'=>1,'max' => 100],
//            [['SERVICE_TAX'], 'number', 'min'=>1,'max' => 100],
            [['SURCHARGES'], 'number','min'=>0, 'max' => 100],
            [['PARTNER_STATUS'], 'string'],
//            [['MOBILE'], 'match', 'pattern' => '/^[0-9]{10,10}$/', 'message'=>'Enter valid Mobile Number.'],
            [['MOBILE'], 'number', 'min' => 1, 'max' => 9999999999, 'message'=>'Number is invalid.', 'tooSmall'=>'Number is invalid.', 'tooBig'=>'Number is invalid.'],
            [['CREATED_ON', 'UPDATED_ON', 'SURCHARGES','PHONE_NO'], 'number'],
            [['PARTNER_NAME', 'AIRPAY_USERNAME', 'AIRPAY_PASSWORD', 'VENDOR_REFERENCE_ID'], 'string', 'max' => 50],
        	[['VENDOR_REFERENCE_ID'], 'match', 'pattern' => '/^[0-9a-zA-Z]+$/', 'message'=>'Only numbers and letters are allowed in Partner Reference Id.'],
            [['PARTNER_LOCATION'], 'string', 'max' => 30],
            [['AIRPAY_SECRET_KEY'], 'string', 'max' => 100],
            [['EMAIL_FOOTER','INVOICE_EMAIL_TEMPLATE','INVOICE_SMS_TEMPLATE','REMINDER_INVOICE_EMAIL_TEMPLATE','REMINDER_INVOICE_SMS_TEMPLATE','SERVICE_TAX','VAT_TAX','PARTNER_STATUS','AIRPAY_MERCHANT_ID','AIRPAY_USERNAME','AIRPAY_PASSWORD','AIRPAY_SECRET_KEY','VENDOR_REFERENCE_ID','ACCOUNT_TYPE','ACCOUNT_HOLDER_NAME','IFSC_CODE','BANK_NAME','BRANCH','BANK_ADDRESS','CITY','STATE','APPROVER_ID','GSTNUM'], 'safe'],
            [['INVOICE_SMS_TEMPLATE','REMINDER_INVOICE_SMS_TEMPLATE'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
           /* 'PARTNER_ID' => 'Partner Id',
            'PARTNER_NAME' => 'Partner Name',
            'PARTNER_LOCATION' => 'Partner Location',
            'AIRPAY_MERCHANT_ID' => 'Airpay Merchant  ID',
            'AIRPAY_USERNAME' => 'Airpay Username',
            'AIRPAY_PASSWORD' => 'Airpay Password',
            'AIRPAY_SECRET_KEY' => 'Airpay Secret  Key',
            'EMAIL_FOOTER' => 'Email  Footer',
            'PARTNER_STATUS' => 'Partner  Status',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
            'MOBILE' => 'Mobile',
            'MERCHANT_ID' => 'Merchant',
            'SERVICE_TAX' => 'Service Tax No.',
            'VAT_TAX' => 'VAT No.',
            'SURCHARGES' => 'Surcharge ( % )',
            'VENDOR_LOGO' => 'Partner Logo',
            'LOGO' => 'Partner Logo',
            'INVOICE_EMAIL_TEMPLATE' => 'Invoice Email Template',
            'INVOICE_SMS_TEMPLATE' => 'Invoice SMS Template',
            'REMINDER_INVOICE_EMAIL_TEMPLATE' => 'Reminder Invoice Email Template',
            'REMINDER_INVOICE_SMS_TEMPLATE' => 'Reminder Invoice SMS Template',
        	//'CSV' => 'CSV File'*/
            'PARTNER_ID' => 'Partner Id',
            'PARTNER_NAME' => 'Partner Name',
            'PARTNER_LOCATION' => 'Partner Location',
            'AIRPAY_MERCHANT_ID' => 'Airpay Merchant  ID',
            'AIRPAY_USERNAME' => 'Airpay Username',
            'AIRPAY_PASSWORD' => 'Airpay Password',
            'AIRPAY_SECRET_KEY' => 'Airpay Secret  Key',
            'EMAIL_FOOTER' => 'Email  Footer',
            'PARTNER_STATUS' => 'Partner  Status',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
            'MOBILE' => 'Mobile',
            'MERCHANT_ID' => 'Merchant',
            //'IMPORT_MERCHANT_ID' => 'Merchant',
            'SERVICE_TAX' => 'Service Tax No.',
            'VAT_TAX' => 'VAT No.',
            'SURCHARGES' => 'Surcharge ( % )',
            'VENDOR_LOGO' => 'Partner Logo',
            'PAN_CARD_LOGO' => 'Corporate Pan Card',
            'LOGO' => 'Partner Logo',
            'PAN_LOGO' => 'Pan Card Logo',
            'INVOICE_EMAIL_TEMPLATE' => 'Invoice Email Template',
            'INVOICE_SMS_TEMPLATE' => 'Invoice SMS Template',
            'REMINDER_INVOICE_EMAIL_TEMPLATE' => 'Reminder Invoice Email Template',
            'REMINDER_INVOICE_SMS_TEMPLATE' => 'Reminder Invoice SMS Template',
            'APPROVER_ID' => 'Approver',
            'VENDOR_REFERENCE_ID' => 'Partner Reference Id',
            'BANK_NAME' => 'Bank Name',
            'ACCOUNT_HOLDER_NAME' => 'Account Holder Name',
            'ACCOUNT_TYPE' => 'Account Type',
            'ACCOUNT_NUMBER' => 'Account Number',
            'IFSC_CODE' => 'IFSC Code',
            'BRANCH' => 'Branch',
            'PHONE_NO' => 'Phone Number',
            'BANK_ADDRESS' => 'Bank Address',
            'CITY' => 'City',
            'STATE' => 'State',
            'CORPORATE_PAN_CARD_NUMBER' => 'Pan Card Number',
            'GSTNUM' => 'GSTIN Number',
            'CATEGORIES' => 'Categories',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getINVOICE()
    {
        return $this->hasMany(Invoice::className(), ['PARTNER_ID' => 'PARTNER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(MerchantMaster::className(), ['MERCHANT_ID' => 'MERCHANT_ID']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover()
    {
        return $this->hasOne(UserMaster::className(), ['USER_ID' => 'APPROVER_ID']);
    }

    public function getCategories()
    {
        return $this->hasMany(PartnerCategory::className(), ['PARTNER_ID' => 'PARTNER_ID']);
    }

    public function beforeValidate()
    {
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE != 'admin') {
                    $this->MERCHANT_ID = Yii::$app->getUser()->identity->MERCHANT_ID;
                }
            }
        }
     
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if($this->SURCHARGES == null)   {
            $this->SURCHARGES = 0;
        }
        if($this->APPROVER_ID == null)   {
            $this->APPROVER_ID = 0;
        }
    
        $this->LOGO = UploadedFile::getInstance($this,'LOGO');
        if(!empty($this->LOGO))  {
            $extension = explode('.',$this->LOGO->name);
            $ext = end($extension);
            $filename = md5(time().$this->LOGO->name).'.'.$ext;
            if($this->LOGO->saveAs(Yii::$app->basePath.'/web/uploads/vendor_logo/'.$filename)) {
                if(!empty($this->VENDOR_LOGO))  {
                    unlink(Yii::$app->basePath.'/web/uploads/vendor_logo/'.$this->VENDOR_LOGO);
                }
                $this->VENDOR_LOGO = $filename;
            }
        }
        $this->PAN_LOGO = UploadedFile::getInstance($this, 'PAN_LOGO');
        if(!empty($this->PAN_LOGO))  {
            $extension = explode('.',$this->PAN_LOGO->name);
            $ext = end($extension);
            $logofilename = md5(time().$this->PAN_LOGO->name).'.'.$ext;
            if($this->PAN_LOGO->saveAs(Yii::$app->basePath.'/web/uploads/vendor_logo/'.$logofilename)) {
                if(!empty($this->PAN_CARD_LOGO))  {
                    unlink(Yii::$app->basePath.'/web/uploads/vendor_logo/'.$this->PAN_CARD_LOGO);
                }
                $this->PAN_CARD_LOGO = $logofilename;
            }
        }
        
        if($insert)  {
            $this->CREATED_ON = time();
        	$this->PARTNER_STATUS = "E";
        }   else    {
            $this->UPDATED_ON = time();
        }

        return parent::beforeSave($insert);
    }

    public static function find()
    {
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'partner') {
                    return parent::find()->andWhere([
                        Partner::tableName().'.PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ]);
                }

                if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                    return parent::find()->andWhere([
                        'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                    ]);
                }
            }
        }
        return parent::find();
    }
}
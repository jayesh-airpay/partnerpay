<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property integer $INVOICE_ID
 * @property integer $PARTNER_ID
 * @property integer $ASSIGN_TO
 * @property string $REF_ID
 * @property string $IS_CORPORATE
 * @property string $COMPANY_NAME
 * @property string $CLIENT_EMAIL
 * @property double $CLIENT_MOBILE
 * @property string $MAIL_SENT
 * @property string $ATTACHMENT
 * @property string $APPLY_SURCHARGE
 * @property double $AMOUNT
 * @property string $SERVICE_TAX
 * @property string $VAT
 * @property double $SURCHARGE_AMOUNT
 * @property double $TOTAL_AMOUNT
 * @property double $PAID
 * @property double $BALANCE
 * @property double $ISSUE_DATE
 * @property double $DUE_DATE
 * @property integer $CREATED_BY
 * @property integer $INVOICE_STATUS
 * @property string $BELONGS_TO_GROUP
 * @property string $PAYMENT_CUSTOM_MESSAGE
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
  * @property integer $IS_APPROVE
 * @property double $INVOICE_BITLY_URL
 *
 * @property Partner $partner
 * @property GroupInvoiceMap[] $groupInvoiceMaps
 */
class Invoice extends \yii\db\ActiveRecord
{
    public $pay_amount = 0;
    public $pay_method;
    public $pay_comment;
    public $iagree;
    public $upcsv;
    public $ATTACHMENTPDF;
    public $active_status;

    const SCENARIO_PAYMENT = 'payment';
    const SCENARIO_APAYMENT = 'addpayment';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_invoice';
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['SCENARIO_PAYMENT'] = $scenarios['default'] + ['COMPANY_NAME', 'iagree', 'pay_amount'];
        return $scenarios;
    }

    public static function find()
    {
        $find = parent::find();
       if(!Yii::$app instanceof \yii\console\Application) {
           if(!Yii::$app->user->isGuest)  {
               if(Yii::$app->getUser()->identity->USER_TYPE == 'partner')    {
                   $find->andWhere([
                       'ASSIGN_TO' => Yii::$app->getUser()->identity->USER_ID
                   ]);
               }   elseif(Yii::$app->getUser()->identity->USER_TYPE == 'merchant'  || Yii::$app->getUser()->identity->USER_TYPE == 'payment') {
                   $find->joinWith('partner')->andWhere([Partner::tableName().'.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID]);
               }elseif(Yii::$app->getUser()->identity->USER_TYPE == 'approver') {                  
                   $partnerdata = Partner::find()->where(['APPROVER_ID' => Yii::$app->getUser()->identity->USER_ID])->one();
                   if(!empty($partnerdata)) {
                   $assignuser = UserMaster::find()->where(['PARTNER_ID' => $partnerdata['PARTNER_ID']])->one();
                   if (!empty($assignuser)) {
                       $find->andWhere([
                           'ASSIGN_TO' => $assignuser['USER_ID']
                       ]);
                   }
                 }

               }
           }

           if(Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && Yii::$app->getUser()->identity->USER_TYPE != 'merchant'))  {
               $find->andWhere([
                   'BELONGS_TO_GROUP' => 'N'
               ]);
           }

       }


        return $find;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CLIENT_EMAIL', 'CLIENT_MOBILE', 'AMOUNT'], 'required','on'=>'paymentpay'],
            [['PARTNER_ID', 'ASSIGN_TO', 'REF_ID', 'CLIENT_EMAIL', 'CLIENT_MOBILE', 'AMOUNT', 'ISSUE_DATE', 'DUE_DATE'], 'required'],
            [['PARTNER_ID', 'ASSIGN_TO', 'CREATED_BY', 'INVOICE_STATUS'], 'integer'],
            [['pay_method'], 'required', 'on' => 'addpayment'],
            [['pay_amount'], 'required', 'on' => 'payment'],
            [['pay_amount'], 'validPayAmount', 'on' => [Invoice::SCENARIO_PAYMENT, Invoice::SCENARIO_APAYMENT]],
            [['IS_CORPORATE', 'MAIL_SENT', 'APPLY_SURCHARGE'], 'string'],
            [['SURCHARGE_AMOUNT', 'TOTAL_AMOUNT', 'PAID', 'BALANCE', 'CREATED_ON', 'UPDATED_ON'], 'number'],
            [['AMOUNT'], 'number', 'min' => 1, 'max' => 9999999999.99, 'message'=>'Amount is invalid.', 'tooSmall'=>'Amount is invalid.', 'tooBig'=>'Amount is invalid.'],
            [['CLIENT_MOBILE'], 'number', 'min' => 1, 'max' => 9999999999, 'message'=>'Number is invalid.', 'tooSmall'=>'Number is invalid.', 'tooBig'=>'Number is invalid.'],
            [['REF_ID'], 'string', 'max' => 20],
            [['COMPANY_NAME'], 'string', 'max' => 100],
            [['iagree'], 'compare', 'compareValue' => true, 'on' => 'payment',
                'message' => 'You must agree to the terms and conditions' ],
            //[['COMPANY_NAME'], 'safe'],
            [['ATTACHMENT'], 'string', 'max' => 36],
            [['ATTACHMENTPDF'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'message' => 'Invalid pdf file.', 'wrongExtension' => 'Invalid pdf file.'],
            //[['REF_ID'], 'unique'],
            [['REF_ID'], 'validateRefid'],
            [['REF_ID'], 'match', 'pattern'=>'/^([0-9a-zA-Z]+)$/', 'message'=>'Only letters and number allows.'],
            [['pay_amount'], 'number','min'=>1, 'message'=>'Amount is invalid.', 'tooSmall'=>'Amount is invalid.', 'on'=>[Invoice::SCENARIO_PAYMENT]],
            //[['CLIENT_MOBILE'], 'match', 'pattern' => '/^[0-9]{10,10}$/', 'message'=>'Enter valid Mobile Number.'],
            [['CLIENT_EMAIL'], 'email', 'skipOnEmpty'=>true],
            [['APPLY_SURCHARGE', 'SERVICE_TAX', 'VAT','PAYMENT_CUSTOM_MESSAGE','INVOICE_BITLY_URL'], 'safe'],
            //[['ISSUE_DATE'],'compare','compareAttribute'=>'DUE_DATE','operator'=>'<'],
           // [['DUE_DATE'],'compare','compareAttribute' => 'ISSUE_DATE','operator'=>'>', 'message' => '{attribute} should be greater than "{compareValue}".'],
        	 //[['ISSUE_DATE'],'validDate'],  //old code
         [['ISSUE_DATE'],'validDate','on'=>'insert,update'],
        ];
    }

    public function validPayAmount($attr, $param)    {
        if($this->pay_amount < 0 ){
            $this->addError($attr,"Amount is invalid.");
        } else if($this->pay_amount > $this->BALANCE) {
            $this->addError($attr,"Payment Amount could not be greater than Balance Amount.");
        } else if($this->pay_amount == 0){
            $this->addError($attr,"Amount is invalid.");
        }
    }

	public function validDate($attr, $param)    {
      // echo '<pre>';print_r($this);exit;
        $start_date = strtotime($this->ISSUE_DATE);
        $end_date = strtotime($this->DUE_DATE);
    
        if(!strtotime($this->ISSUE_DATE)){
              $start_date = $this->ISSUE_DATE;
              $end_date = $this->DUE_DATE;
        }
        if($start_date > $end_date ){
      //  if($start_date < $end_date ){
            $this->addError($attr,"Issue Date must be less than \"Due Date\".");
        }

    }

	public function validateRefid($attr, $param)    {
        if(Yii::$app->user->identity->USER_TYPE == 'partner' || Yii::$app->user->identity->USER_TYPE == 'merchant') {
            if(!$this->isNewRecord) {
                $data = RefUnique::find()->andWhere(['<>','INVOICE_ID', $this->INVOICE_ID])->andWhere(['REF_ID' => $this->REF_ID])->joinWith('partner')->andWhere([Partner::tableName() . '.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
            } else {
                $data = RefUnique::find()->andWhere(['REF_ID' => $this->REF_ID])->joinWith('partner')->andWhere([Partner::tableName() . '.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
            }

            if (!empty($data)) {
                $this->addError('REF_ID', 'Reference Number "'.$this->REF_ID.'" has already been taken.');
            }
        }
        if(Yii::$app->user->identity->USER_TYPE == 'admin') {
            $data = RefUnique::find()->andWhere(['REF_ID' => $this->REF_ID])->all();
            if (!empty($data)) {
                $this->addError('REF_ID', 'Reference Number "'.$this->REF_ID.'" has already been taken.');
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'INVOICE_ID' => '#',
            'PARTNER_ID' => 'Partner (Vendors)',
            'MERCHANT_ID' => 'Merchant',
            'REF_ID' => 'Reference Number',
            'CREATED_BY' => 'Created  By',
            'ASSIGN_TO' => 'Invoice For Partner',
            'IS_CORPORATE' => 'Corporate',
            'PO_ID' => 'PO Number',
            'APPLY_SURCHARGE' => 'Apply Surcharges',
            'COMPANY_NAME' => 'Company  Name',
            'CLIENT_EMAIL' => 'Client  Email',
            'CLIENT_MOBILE' => 'Client  Mobile',
            'MAIL_SENT' => 'Mail  Sent',
            'AMOUNT' => 'Invoice Amount',
            'SURCHARGE_AMOUNT' => 'Surcharge Amount',
            'BELONGS_TO_GROUP' => 'Belongs To Group',
            'pay_amount' => 'Amount',
            'PAID' => 'Amount Paid',
            'BALANCE' => 'Balance Amount',
            'INVOICE_STATUS' => 'Invoice  Status',
            'ATTACHMENT' => 'Attachment',
            'ATTACHMENTPDF' => 'Invoice PDF',
            'ISSUE_DATE' => 'Issue  Date',
            'DUE_DATE' => 'Due  Date',
            'CREATED_ON' => 'Created  On',
            'UPDATED_ON' => 'Updated  On',
            'iagree' => 'I agree',
            'upcsv' => 'Csv File',
            'pay_method' => 'Payment Method',
            'pay_comment' => 'Comment',
        	'IS_APPROVE' => 'Is Approved?',
        ];
    }

    public function validCompanyName($attr, $par) {        //echo "asd"; exit;
        //var_dump($this->IS_CORPORATE); exit;
        if($this->IS_CORPORATE == 1 && empty($this->$attr)) {
            $this->addError($attr,'Please enter Company Name.');
        }
        return true;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['PARTNER_ID' => 'PARTNER_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUSERMERCHANT()
    {
        return $this->hasOne(UserMerchant::className(), ['ASSIGN_TO' => 'USER_ID']);
    }

    public function getGroupInvoiceMaps()
    {
        return $this->hasMany(GroupInvoiceMap::className(), ['INVOICE_ID' => 'INVOICE_ID']);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getPo()
    {
        return $this->hasOne(PoMaster::className(), ['PO_ID' => 'PO_ID', 'PO_NUMBER' => 'PO_NUMBER']);
    }

    public function beforeValidate() {
        if($this->PO_ID == null)   {
            $this->PO_ID = 0;
        }

        foreach($this->attributes as $key => $value)  {
            if(is_string($value))   {
                $this->$key = trim($value);
            }
        }

        if(!Yii::$app->user->isGuest)  {
            if(Yii::$app->user->identity->USER_TYPE != 'partner')    {
                if(!empty($this->ASSIGN_TO))    {
                    $user_model = UserMaster::find()->where(['USER_ID' => $this->ASSIGN_TO])->one();
                    $this->PARTNER_ID = $user_model->PARTNER_ID;

                }
            } else {
                $this->PARTNER_ID = Yii::$app->user->identity->PARTNER_ID;
                $this->ASSIGN_TO = Yii::$app->user->identity->USER_ID;
            }
        }


        if(!empty($this->pay_amount))   {
            $this->pay_amount = sprintf("%0.2f", round($this->pay_amount,2));
        }


        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->pay_amount = $this->BALANCE;
        return parent::afterFind();
    }


    public function beforeSave($insert)
    {

       if(!is_numeric($this->ISSUE_DATE)) {
            $this->ISSUE_DATE = strtotime($this->ISSUE_DATE);
        }
        
        if(!is_numeric($this->DUE_DATE)) {
            $this->DUE_DATE = strtotime($this->DUE_DATE);
        }
    
        $partner = Partner::findOne($this->PARTNER_ID);
    
        if($insert)  {
            if(!empty($partner))    {
                $this->SERVICE_TAX = $partner->SERVICE_TAX;
                $this->VAT = $partner->VAT_TAX;
            }

            if(!empty($this->APPLY_SURCHARGE))  {

                if(!empty($partner))    {
                    $this->SURCHARGE_AMOUNT = $this->AMOUNT * $partner->SURCHARGES / 100;
                }
            }

            $this->TOTAL_AMOUNT = $this->AMOUNT + $this->SURCHARGE_AMOUNT;
            $this->BALANCE = $this->TOTAL_AMOUNT;
        }   else    {

            if(!empty($this->APPLY_SURCHARGE))  {
                if(!empty($partner))    {
                    $this->SURCHARGE_AMOUNT = $this->AMOUNT * $partner->SURCHARGES / 100;
                }
            } else {
                $this->TOTAL_AMOUNT = $this->AMOUNT;
                $this->SURCHARGE_AMOUNT = 0;
                $this->SERVICE_TAX =0;
                $this->VAT = 0;
            }


            $this->TOTAL_AMOUNT = $this->AMOUNT + $this->SURCHARGE_AMOUNT;

            $this->BALANCE = $this->TOTAL_AMOUNT - $this->PAID;

            if($this->BALANCE < 0){
                $this->BALANCE = 0.00;
            }


            if($this->BALANCE > $this->TOTAL_AMOUNT)  {
                $this->BALANCE = $this->TOTAL_AMOUNT;
            }

        }

        if($insert)  {
            $this->CREATED_BY = Yii::$app->user->identity->USER_ID;
            $this->CREATED_ON = time();
            $this->INVOICE_STATUS = 0;
        }   else    {
            $this->UPDATED_ON = time();
        }

        return parent::beforeSave($insert);
    }

	function functionName($model)
    {
        if (empty($model->INVOICE_STATUS)) {
            $status = '<b>Pending</b>';
        } else {
            $status = '<b>Paid</b>';
        }
        return $model->INVOICE_ID. "  (" .$status.")";
    }

    function getSurcharge($model)
    {        
        if ($model->APPLY_SURCHARGE== 1) {
            $status = '<b> (Apply Surcharges)</b>';
        } else {
            $status = '';
        }
    $str = "Rs. ".sprintf("%0.2f", $model->AMOUNT). $status;
        return  $str;
    }
}

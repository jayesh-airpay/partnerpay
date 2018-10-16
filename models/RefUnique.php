<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%invoice}}".
 *
 * @property integer $INVOICE_ID
 * @property integer $PARTNER_ID
 * @property integer $ASSIGN_TO
 * @property integer $PO_ID
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
 * @property PoMaster $po
 */
class RefUnique extends \yii\db\ActiveRecord
{
    public $pay_amount = 0;
    public $pay_method;
    public $pay_comment;
    public $iagree;
    public $upcsv;
    public $ATTACHMENTPDF;
    public $active_status;

    const SCENARIO_INSERT = 'insert';
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
        $scenarios[self::SCENARIO_INSERT]= $scenarios['default'] + ['REF_ID'];
        $scenarios['SCENARIO_PAYMENT'] = $scenarios['default'] + ['COMPANY_NAME', 'iagree', 'pay_amount'];
        return $scenarios;
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['REF_ID'], 'unique'],
        ];
    }


    /*public function validateRefid($attr, $param)    {
        //var_dump($attr);
        //$find = parent::find();
        if(Yii::$app->user->identity->USER_TYPE == 'partner') {
            $data = Invoice::find()->andWhere(['REF_ID' => $this->REF_ID])->joinWith('partner')->andWhere([Partner::tableName() . '.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
            if (!empty($data)) {
                $this->addError('REF_ID', 'Ref Id should be unique.');
            }
        }
        if(Yii::$app->user->identity->USER_TYPE == 'admin') {
            $data = Invoice::find()->andWhere(['REF_ID' => $this->REF_ID])->all();
            if (!empty($data)) {
                $this->addError('REF_ID', 'Ref Id should be unique.');
            }
        }

    }*/

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


}

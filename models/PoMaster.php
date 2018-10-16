<?php

namespace app\models;

use Yii;
use yii\web\Application;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%po_master}}".
 *
 * @property integer $PO_ID
 * @property integer $MERCHANT_ID
 * @property string $PARTNER_ID
 * @property string $SAP_REFERENCE
 * @property string $PO_NUMBER
 * @property double $DATE_OF_CREATION
 * @property double $AMOUNT
 * @property string $IS_PAID
 * @property string $PDF_ATTACHMENT
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 *
 * @property Invoice[] $invoices
 * @property MerchantMaster $Merchant
 */
class PoMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%po_master}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MERCHANT_ID', 'PARTNER_ID', 'PO_NUMBER', 'AMOUNT'], 'required'],
            [['MERCHANT_ID','PARTNER_ID'], 'integer'],
            [['QUOTATION_ID'],'integer','on' => 'withqr' ],
            [['AMOUNT', 'CREATED_ON', 'UPDATED_ON'], 'number'],
            [['SAP_REFERENCE', 'PO_NUMBER'], 'string', 'max' => 25],
            [['PDF_ATTACHMENT'], 'string', 'max' => 37],
            [['DATE_OF_CREATION', 'PDF_ATTACHMENT'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PO_ID' => 'PO Id',
            'MERCHANT_ID' => 'Merchant Name',
            'PARTNER_ID' => 'Partner Name',
            'SAP_REFERENCE' => 'SAP Reference',
            'PO_NUMBER' => 'PO Number',
            'DATE_OF_CREATION' => 'Date Of Creation',
            'PAYMENT_DUE_DATE' => 'Payment Due Date',
            'AMOUNT' => 'Amount',
            'PDF_ATTACHMENT' => 'PDF Attachment',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
            'IS_PAID' => 'Invoice Payment Status',
           'QUOTATION_ID' => 'QUOTATION ID',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['PO_ID' => 'PO_ID']);
    }

    public function afterFind()
    {
        $this->DATE_OF_CREATION = empty($this->DATE_OF_CREATION)?null:date("d M Y", $this->DATE_OF_CREATION);
        parent::afterFind();
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
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['PARTNER_ID' => 'PARTNER_ID']);
    }

    public function beforeSave($insert)
    {
        if(!is_numeric($this->DATE_OF_CREATION))   {
            $this->DATE_OF_CREATION = strtotime($this->DATE_OF_CREATION);
        }

        $this->PDF_ATTACHMENT = UploadedFile::getInstance($this,'PDF_ATTACHMENT');
        //var_dump( $this->PDF_ATTACHMENT); exit;
        if(!empty($this->PDF_ATTACHMENT))  {
            $extension = explode('.',$this->PDF_ATTACHMENT->name);
            $ext = end($extension);
            $filename = md5(time().$this->PDF_ATTACHMENT->name).'.'.$ext;

            if($this->PDF_ATTACHMENT->saveAs(Yii::$app->basePath.'/web/uploads/pdf/'.$filename)) {
                if(!empty($this->PDF_ATTACHMENT)) {
                    unlink(Yii::$app->basePath.'/web/uploads/pdf/'.$this->PDF_ATTACHMENT);
                }
                $this->PDF_ATTACHMENT = $filename;
            }
        }

        if($insert) {
            $this->CREATED_ON = time();
            $this->UPDATED_ON = 0;
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
                        'PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ]);
                }

                if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                    return parent::find()->andWhere([
                        'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                    ]);
                }
            }
        }
        //var_dump(parent::find()); exit;
        return parent::find();

    }

    public function beforeValidate()
    {
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                    //var_dump(Yii::$app->getUser()->identity->MERCHANT_ID); exit;
                    $this->MERCHANT_ID = Yii::$app->getUser()->identity->MERCHANT_ID;
                }
                if (Yii::$app->getUser()->identity->USER_TYPE == 'partner') {
                    $this->PARTNER_ID = Yii::$app->getUser()->identity->PARTNER_ID;
                }
            }
        }
        return parent::beforeValidate();
    }
}

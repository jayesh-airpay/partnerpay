<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
/**
 * This is the model class for table "tbl_quotation_master".
 *
 * @property integer $ID
 * @property string $NAME
 * @property string $DESCRIPTION
 * @property integer $CAT_ID
 * @property integer $MERCHANT_ID
 * @property integer $PARENT_ID
 * @property integer $VERSION_ID
 * @property double $DUE_DATE
 * @property string $STATUS
 * @property integer $ASSIGN_PARTNER
 * @property integer $ASSIGN_DATE
 * @property double $CREATED
 * @property double $MODIFIED
 * @property double $FILE
 */
class Quotation extends \yii\db\ActiveRecord
{
    public $fileinput;
    public $PARTNERS;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_quotation_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //   [['NAME', 'DESCRIPTION', 'CAT_ID', 'PARENT_ID', 'VERSION_ID', 'DUE_DATE', 'STATUS', 'ASSIGN_PARTNER', 'ASSIGN_DATE', 'CREATED', 'MODIFIED'], 'required'],
            [['NAME', 'DESCRIPTION', 'CAT_ID', 'DUE_DATE', 'STATUS','MERCHANT_ID'], 'required'],
           // ['NAME','unique'],
               [['NAME', 'DESCRIPTION', 'CAT_ID', 'DUE_DATE', 'STATUS','MERCHANT_ID'], 'trim'],
            [['fileinput'], 'file', 'extensions' => 'doc,docx,jpg,jpeg,png,xls,xlsx,pdf','maxSize' => 2000000, 'tooBig' => 'Limit is 2MB', 'skipOnEmpty' => false, 'skipOnError' => false, 'on' => 'insert'],
            [['fileinput'], 'file', 'extensions' => 'doc,docx,jpg,jpeg,png,xls,xlsx,pdf', 'skipOnEmpty' => true, 'on' => 'update'],
            [['CAT_ID', 'PARENT_ID', 'VERSION_ID', 'ASSIGN_DATE','MERCHANT_ID'], 'integer'],
            //[['AMOUNT'], 'number','min' => 1,'max' => '10000000','message' => 'Amount is invalid.'],
            [['CREATED', 'MODIFIED'], 'number'],
            [['STATUS'], 'string'],
            [['NAME', 'DESCRIPTION'], 'string', 'max' => 255],
            [['FILE'], 'string', 'max' => 50],
            ['NEW_PARTNER_EMAIL','email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'QR ID',
            'NAME' => 'QR Name',
            'DESCRIPTION' => 'Description',
            'CAT_ID' => 'Category',
            'PARENT_ID' => 'Parent',
            'VERSION_ID' => 'Version',
            'DUE_DATE' => 'Due  Date',
            'STATUS' => 'Status',
            'ASSIGN_PARTNER' => 'Vendor',
            'ASSIGN_DATE' => 'Assign  Date',
            'NEW_PARTNER_EMAIL' => 'New Partner Email',
            'FILE' => 'Upload File',
            'MERCHANT_ID' => 'Merchant Name',
            'CREATED' => 'Created Date',
            'MODIFIED' => 'Modified',
            'PARTNERS' => 'Partner',
        ];
    }
    public function beforeSave($insert)
    {
        if($insert)  {
            $this->CREATED = strtotime(date('d-m-Y'));
        }   else    {
            $this->MODIFIED = time();
        }

        return parent::beforeSave($insert);
    }
    public function getPartnersdata(){
        return $this->hasMany(TblQuotationPartners::className(), ['QUOTATION_ID' => 'ID']);
    }


    public static function find()
    {
       // echo '<pre>';print_r(Yii::$app->request->url);exit;
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {

                    return parent::find()->where([
                        'MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID
                    ]);
                }
            }
        }
        return parent::find();
    }


}
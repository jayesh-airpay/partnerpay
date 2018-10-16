<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_quotation_partners".
 *
 * @property integer $ID
 * @property integer $QUOTATION_ID
 * @property integer $PARTNER_ID
 * @property string $PARTNER_UPLOADED_DOC
 * @property double $AMOUNT
 * @property double $CREATED
 */
class TblQuotationPartners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_quotation_partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['QUOTATION_ID', 'PARTNER_ID', 'CREATED'], 'required'],
            [['QUOTATION_ID', 'PARTNER_ID'], 'integer'],
            [['AMOUNT'], 'number','min' => 1,'max' => '10000000','message' => 'Amount is invalid.'], 
            [['AMOUNT'],'trim'],
            [['CREATED'], 'number'],
            [['PARTNER_UPLOADED_DOC'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'QUOTATION_ID' => 'Quotation  ID',
            'PARTNER_ID' => 'Vendor Name',
            'PARTNER_UPLOADED_DOC' => 'Doc Download',
            'AMOUNT' => 'Amount',
            'CREATED' => 'Created',
            'UPDATED' => 'Updated',
        ];
    }

        public static function find()
    {
     
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'guestuser') {
                    if(Yii::$app->request->url != '/quotation/listofquotationsassigned'){
                     return parent::find()->andWhere([
                         TblQuotationPartners::tableName().'.PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ])
                    ->andWhere([
                         '<>',TblQuotationPartners::tableName().'.AMOUNT',''
                    ]);
                  }else{
                
                  return parent::find()->andWhere([
                         TblQuotationPartners::tableName().'.PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ])
                    ->andWhere([
                         'is',TblQuotationPartners::tableName().'.AMOUNT',NULL
                    ]);
              
                   }                }
            
             
              
               if (Yii::$app->getUser()->identity->USER_TYPE == 'partner' ) {
               
                 
               
                if(Yii::$app->request->url != '/quotation/listofquotationsassigned'){
                     return parent::find()->andWhere([
                         TblQuotationPartners::tableName().'.PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ])
                    ->andWhere([
                         '<>',TblQuotationPartners::tableName().'.AMOUNT',''
                    ]);
                  }else{
                
                  return parent::find()->andWhere([
                         TblQuotationPartners::tableName().'.PARTNER_ID' => Yii::$app->getUser()->identity->PARTNER_ID
                    ])
                    ->andWhere([
                         'is',TblQuotationPartners::tableName().'.AMOUNT',NULL
                    ]);
              
                   }
                
                }
            
               // echo '<pre>';print_r( Yii::$app->getUser()->identity);exit;
                 if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                     return parent::find()
                    ->andWhere([
                         '<>',TblQuotationPartners::tableName().'.AMOUNT',''
                    ]);
                }
            
                if (Yii::$app->getUser()->identity->USER_TYPE == 'admin') {
                     return parent::find()->andWhere([
                         '<>',TblQuotationPartners::tableName().'.AMOUNT',''
                    ]);
                }

            }
        }
        return parent::find();
    }
}

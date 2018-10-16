<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_order".
 *
 * @property integer $ORDER_ID
 * @property integer $INVOICE_ID
 * @property integer $PAYMENT_METHOD
 * @property double $RECEIVED_AMOUNT
 * @property integer $PAYMENT_STATUS
 * @property integer $TRANSACTION_ID
 * @property integer $TRANSACTION_STATUS
 * @property string $TRANSACTION_MESSAGE
 * @property string $BELONGS_TO_GROUP
 * @property double $CREATED_ON
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get(!empty(Yii::$app->controller->DB_name)?Yii::$app->controller->DB_name:'db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['INVOICE_ID', 'CREATED_ON'], 'required'],
            //[['INVOICE_ID', 'RECEIVED_AMOUNT', 'PAYMENT_STATUS', 'TRANSACTION_ID', 'TRANSACTION_STATUS', 'TRANSACTION_MESSAGE', 'CREATED_ON'], 'required'],
            [['RECEIVED_AMOUNT', 'PAYMENT_STATUS', 'TRANSACTION_ID', 'TRANSACTION_STATUS', 'TRANSACTION_MESSAGE'], 'required', 'on' =>'transaction'],
            [['PAYMENT_METHOD'], 'required', 'on' => 'addpayment'],
            [['INVOICE_ID', 'PAYMENT_METHOD', 'PAYMENT_STATUS', 'TRANSACTION_ID', 'TRANSACTION_STATUS'], 'integer'],
            [['RECEIVED_AMOUNT', 'CREATED_ON'], 'number'],
            [['TRANSACTION_MESSAGE'], 'string', 'max' => 50],
            [['BELONGS_TO_GROUP'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ORDER_ID' => 'Order  ID',
            'INVOICE_ID' => 'Invoice  ID',
            'PAYMENT_METHOD' => 'Payment  Method',
            'RECEIVED_AMOUNT' => 'Received  Amount',
            'PAYMENT_STATUS' => 'Payment  Status',
            'TRANSACTION_ID' => 'Transaction  ID',
            'TRANSACTION_STATUS' => 'Transaction  Status',
            'TRANSACTION_MESSAGE' => 'Transaction  Message',
            'CREATED_ON' => 'Created  On',
        ];
    }
}

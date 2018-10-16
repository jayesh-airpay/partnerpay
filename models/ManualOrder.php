<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_manual_order".
 *
 * @property integer $MANUAL_ORDER_ID
 * @property integer $ORDER_ID
 * @property integer $USER_ID
 */
class ManualOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_manual_order';
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
            [['ORDER_ID', 'USER_ID'], 'required'],
            [['ORDER_ID', 'USER_ID'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'MANUAL_ORDER_ID' => 'Manual  Order  ID',
            'ORDER_ID' => 'Order  ID',
            'USER_ID' => 'User  ID',
        ];
    }
}

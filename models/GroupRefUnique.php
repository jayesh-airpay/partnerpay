<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%group_invoice}}".
 *
 * @property integer $GROUP_INVOICE_ID
 * @property integer $GROUP_ID
 * @property double $AMOUNT
 * @property double $SERVICE_CHARGE
 * @property double $TOTAL_AMOUNT
 * @property integer $INVOICE_STATUS
 * @property double $CREATED_ON
 * @property double $PAYMENT_DATE
 * @property double $UPDATED_ON
 * @property string $GI_REF_ID
 *
 * @property Partner $partner
 * @property Group $group
 * @property GroupInvoiceMap[] $groupInvoiceMaps
 */
class GroupRefUnique extends \yii\db\ActiveRecord
{
    public $PARTNER_ID;
    public $PARTNER_NAME;
    public $PAN_NO;
    public $iagree;

    const SCENARIO_PAYMENT = 'payment';
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_APAYMENT = 'addpayment';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_invoice}}';
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['SCENARIO_INSERT'] = $scenarios['default'] + ['GI_REF_ID'];
        //$scenarios['SCENARIO_INSERT'] = $scenarios['default'] + ['PARTNER_ID', 'PARTNER_NAME', 'PAN_NO'];
        return $scenarios;
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['GI_REF_ID'], 'unique'],
        ];
    }





    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['GROUP_ID' => 'GROUP_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupInvoiceMaps()
    {
        return $this->hasMany(GroupInvoiceMap::className(), ['GROUP_INVOICE_ID' => 'GROUP_INVOICE_ID']);
    }
}

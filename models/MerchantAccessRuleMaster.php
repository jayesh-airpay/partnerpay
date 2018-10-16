<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tbl_merchant_master".
 *
 * @property string $M_ACCESS_RULE_ID
 * @property string $USER_ID
 * @property string $CAT_ID
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 */
class MerchantAccessRuleMaster extends \yii\db\ActiveRecord
{
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_merchant_access_rules';
    }

    public function scenarios() {
		$scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INSERT] = array_merge($scenarios['default'] , ['USER_ID','CAT_ID']);
        $scenarios[self::SCENARIO_UPDATE] = array_merge($scenarios['default'] , ['CAT_ID']);
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /*return [
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
            [['USER_ID','CAT_ID'], 'safe'],
        ];*/
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'M_ACCESS_RULE_ID' => 'Merchnat Access Rule Id',
            'USER_ID' => 'User Id',
            'CAT_ID' => 'Category Id',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
        ];
    }

    public function beforeValidate() {
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
		if($insert) {
            $this->CREATED_ON = time();
        }   else {
            $this->UPDATED_ON = time();
        }
    }
}
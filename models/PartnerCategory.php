<?php

namespace app\models;

use Yii;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "tbl_category_master".
 *
 * @property string $CAT_NAME
 * @property string $CAT_DESC
 * @property string $CAT_STATUS
 */
class PartnerCategory extends \yii\db\ActiveRecord
{
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    //public $auth_key;
    /**
     * @inheritdoc
     */
    public static function tableName()
	{
        return 'tbl_partner_categories';
    }
	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_INSERT]= $scenarios['default'] + ['CAT_NAME', 'CAT_DESC'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CAT_NAME', 'CAT_DESC', 'CAT_STATUS'], 'required'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PARTNER_ID' => 'Partner ID',
            'CAT_ID' => 'Category ID',
            'CREATED_ON' => 'Created On',
            'UPDATED_ON' => 'Updated On',
        ];
    }
	
	public function beforeSave($insert)
    {
        if($insert) {
            $this->CREATED = time();
        }
		else {
            $this->UPDATED = time();
        }
        return parent::beforeSave($insert);
    }
}
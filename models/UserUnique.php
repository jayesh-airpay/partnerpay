<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_master}}".
 *
 * @property integer $USER_ID
 * @property integer $MERCHANT_ID
 * @property integer $PARTNER_ID
 * @property string $USER_TYPE
 * @property string $EMAIL
 * @property string $PASSWORD
 * @property string $FIRST_NAME
 * @property string $LAST_NAME
 * @property string $MOBILE
 * @property string $ACCESS_TOKEN
 * @property string $AUTH_KEY
 * @property string $USER_STATUS
 * @property double $CREATED_ON
 * @property double $UPDATED_ON
 */
class UserUnique extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_master}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MERCHANT_ID', 'USER_TYPE', 'EMAIL', 'PASSWORD', 'FIRST_NAME', 'LAST_NAME', 'MOBILE', 'ACCESS_TOKEN', 'AUTH_KEY', 'USER_STATUS', 'CREATED_ON'], 'required'],
            [['MERCHANT_ID', 'PARTNER_ID'], 'integer'],
            [['USER_TYPE', 'USER_STATUS'], 'string'],
            [['CREATED_ON', 'UPDATED_ON'], 'number'],
            [['EMAIL', 'FIRST_NAME', 'LAST_NAME'], 'string', 'max' => 50],
            [['PASSWORD', 'ACCESS_TOKEN', 'AUTH_KEY'], 'string', 'max' => 200],
            [['MOBILE'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'USER_ID' => 'User  ID',
            'MERCHANT_ID' => 'Merchant  ID',
            'PARTNER_ID' => 'Partner  ID',
            'USER_TYPE' => 'User  Type',
            'EMAIL' => 'Email',
            'PASSWORD' => 'Password',
            'FIRST_NAME' => 'First  Name',
            'LAST_NAME' => 'Last  Name',
            'MOBILE' => 'Mobile',
            'ACCESS_TOKEN' => 'Access  Token',
            'AUTH_KEY' => 'Auth  Key',
            'USER_STATUS' => 'User  Status',
            'CREATED_ON' => 'Created  On',
            'UPDATED_ON' => 'Updated  On',
        ];
    }
}

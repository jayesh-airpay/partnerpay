<?php

namespace app\models;

use Yii;
use yii\base\Model;


class ImportPO extends Model
{
    public $CSV;
    public $IMPORT_MERCHANT;
    public $IMPORT_PARTNER;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CSV','IMPORT_MERCHANT','IMPORT_PARTNER'], 'required'],
            [['CSV'], 'file', 'extensions' => ['csv'], 'wrongExtension' => 'Not a valid file.', 'checkExtensionByMimeType'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CSV' => 'CSV File',
            'IMPORT_MERCHANT' => 'Merchant',
            'IMPORT_PARTNER' => 'Partner',
        ];
    }

    public function beforeValidate()
    {
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'merchant') {
                    $this->IMPORT_MERCHANT = Yii::$app->getUser()->identity->MERCHANT_ID;
                }
            }
        }
        return parent::beforeValidate();
    }
}
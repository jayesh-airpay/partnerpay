<?php

namespace app\models;

use Yii;
use yii\base\Model;


class ImportPartner extends Model
{
    public $CSV;
    public $IMPORT_MERCHANT_ID;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CSV', 'IMPORT_MERCHANT_ID'], 'required'],
            [['CSV'], 'file', 'extensions' => ['csv'], 'wrongExtension' => 'Not a valid file.', 'checkExtensionByMimeType'=>false],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IMPORT_MERCHANT_ID' => 'Merchant',
            'CSV' => 'CSV File'

        ];
    }
}

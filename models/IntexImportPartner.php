<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 2/9/16
 * Time: 5:52 PM
 */

namespace app\models;

use Yii;
use yii\base\Model;


class IntexImportPartner extends Model
{
    public $TXT;
    public $IMPORT_MERCHANT_ID;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TXT', 'IMPORT_MERCHANT_ID'], 'required'],
            [['TXT'], 'file', 'extensions' => ['txt'], 'wrongExtension' => 'Not a valid file.', 'checkExtensionByMimeType'=>false],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IMPORT_MERCHANT_ID' => 'Merchant',
            'TXT' => 'Text File'

        ];
    }

}
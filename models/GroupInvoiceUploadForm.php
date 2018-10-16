<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 25/5/16
 * Time: 3:05 PM
 */
namespace app\models;

use Yii;
use yii\base\Model;


class GroupInvoiceUploadForm extends Model
{
    public $group_id;
    public $upload_file;
	//public $GI_REF_ID;

	/*public function attributeLabels()
    {
        return [
            'GI_REF_ID' => 'Reference  ID',
        ];
    }*/


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['group_id'], 'required'],
        	//[['GI_REF_ID'], 'match', 'pattern' => '/^[0-9a-zA-Z]+$/', 'message'=>'Only numbers and letters are allowed in Reference Id.'],
            [['upload_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv', 'checkExtensionByMimeType' => false],
        	//[['GI_REF_ID'], 'validRef'],
        ];
    }


	public function validRef($attr, $param)    {
        if(Yii::$app->user->identity->USER_TYPE == 'partner' || Yii::$app->user->identity->USER_TYPE == 'merchant') {
            /*if(!$this->isNewRecord) {
                $data = GroupRefUnique::find()->andWhere(['<>','GROUP_INVOICE_ID', $this->GROUP_INVOICE_ID])->andWhere(['GI_REF_ID' => $this->GI_REF_ID])->joinWith('group')->andWhere([Group::tableName() . '.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
            } else {*/
                $data = GroupRefUnique::find()->andWhere(['GI_REF_ID' => $this->GI_REF_ID])->joinWith('group')->andWhere([Group::tableName() . '.MERCHANT_ID' => Yii::$app->getUser()->identity->MERCHANT_ID])->all();
            //}

            if (!empty($data)) {
                $this->addError('GI_REF_ID', 'Reference Number "'.$this->GI_REF_ID.'" has already been taken.');
            }
        }
        if(Yii::$app->user->identity->USER_TYPE == 'admin') {
            $data = GroupRefUnique::find()->andWhere(['GI_REF_ID' => $this->GI_REF_ID])->all();
            if (!empty($data)) {
                $this->addError('GI_REF_ID', 'Reference Number "'.$this->GI_REF_ID.'" has already been taken.');
            }
        }

    }
}
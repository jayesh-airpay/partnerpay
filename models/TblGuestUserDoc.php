<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_guest_user_doc".
 *
 * @property integer $ID
 * @property string $DOC_NAME
 * @property string $FILE
 * @property integer $USER_ID
 * @property integer $CREATED
 * @property integer $UPDATED
 */
class TblGuestUserDoc extends \yii\db\ActiveRecord
{
    public $fileinput;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_guest_user_doc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DOC_NAME', 'USER_ID'], 'required'],
            [['fileinput'], 'file', 'extensions' => 'doc,docx,jpg,png,jpeg,pdf', 'skipOnEmpty' => false, 'skipOnError' => false, 'on' => 'insert'],
            [['fileinput'], 'file', 'extensions' => 'doc,docx,jpg,png,jpeg,pdf', 'skipOnEmpty' => true, 'on' => 'update'],
            [['USER_ID', 'CREATED', 'UPDATED'], 'integer'],
            [['DOC_NAME', 'FILE'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'DOC_NAME' => 'Doc  Name',
            'FILE' => 'File',
            'USER_ID' => 'User  ID',
            'CREATED' => 'Created',
            'UPDATED' => 'Updated',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert)  {
            $this->CREATED = time();
        }   else    {
            $this->UPDATED = time();
        }

        return parent::beforeSave($insert);
    }
    public static function find()
    {
      // echo '<pre>';print_r(Yii::$app->getUser()->identity);exit;
        if(!Yii::$app instanceof \yii\console\Application) {
            if (!Yii::$app->user->isGuest) {
                if (Yii::$app->getUser()->identity->USER_TYPE == 'guestuser') {
                    return parent::find()->andWhere([
                        'USER_ID' => Yii::$app->getUser()->identity->USER_ID
                    ]);
                }
            
               if (Yii::$app->getUser()->identity->USER_TYPE == 'partner') {
                    return parent::find()->andWhere([
                        'USER_ID' => Yii::$app->getUser()->identity-USER_ID
                    ]);
               }
            }
        

        }
        return parent::find();
    }
}

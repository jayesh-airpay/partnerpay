<?php
namespace app\commands;

use app\helpers\invoiceHelper;
use app\models\Invoice;
use app\models\UserMaster;
use yii\console\Controller;
use yii\helpers\Html;
use yii\web\UrlManager;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EmailReminderController extends Controller {
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */

        public function actionIndex() {
            
             //$Invoice = Invoice::find()->where(['INVOICE_STATUS'=>0])->all();
          // $Invoice = Invoice::find()->andWhere(['INVOICE_STATUS'=>0,'MAIL_SENT'=>'N'])
           //->andWhere(['<>','CLIENT_EMAIL', ''])->all();
            $Invoice = Invoice::find()->andWhere(['INVOICE_STATUS'=>0])->all();
            foreach($Invoice as $row){
                $ghelper = new invoiceHelper();
                   $send = $ghelper->sendReminderMail($row);
                   $sendsms = $ghelper->sendReminderMail($row);
                   var_dump($send);
                   var_dump($sendsms);
               }
        }
}


?>
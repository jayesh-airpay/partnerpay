<?php

namespace app\modules\spicejet\controllers;

use app\helpers\Checksum;

class AgencyController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $this->enableCsrfValidation = false;
        $partner_id = !empty(\Yii::$app->user->identity->PARTNER_ID) ? \Yii::$app->user->identity->PARTNER_ID : '';
        if (empty($partner_id)) {
            \Yii::$app->user->logout();
            return $this->goHome();
        }
        $connection = \Yii::$app->db;
        $is_payment_done = false;
        if (isset($_POST['TRANSACTIONID']) && isset($_POST['APTRANSACTIONID']) && isset($_POST['AMOUNT']) && isset($_POST['TRANSACTIONSTATUS']) && isset($_POST['MESSAGE']) && isset($_POST['TRANSACTIONTIME']) && isset($_POST['TRANSACTIONTYPE']) && isset($_POST['ap_SecureHash']) && isset($_POST['CHMOD'])) {
            $TRANSACTIONID = trim($_POST['TRANSACTIONID']);
            $APTRANSACTIONID = trim($_POST['APTRANSACTIONID']);
            $AMOUNT = trim($_POST['AMOUNT']);
            $TRANSACTIONSTATUS = trim($_POST['TRANSACTIONSTATUS']);
            $MESSAGE = trim($_POST['MESSAGE']);
            $TRANSACTIONTIME = isset($_POST['TRANSACTIONTIME']) ? trim($_POST['TRANSACTIONTIME']) : date("d-m-Y h:i:s");
            $TRANSACTIONTYPE = trim($_POST['TRANSACTIONTYPE']);
            $ap_SecureHash = trim($_POST['ap_SecureHash']);
            $IPNID = trim($_POST['IPNID']);
            $CHMOD = trim($_POST['CHMOD']);
            $transaction_type_array = array('310', '320', '330', '370', '380', '390', '400', '410');
            if ($TRANSACTIONSTATUS == 200 && in_array($TRANSACTIONTYPE, $transaction_type_array)) {
                $agent_invoice = $connection->createCommand('SELECT A.* FROM tbl_invoice as A WHERE A.REF_ID =:ref_id AND A.PARTNER_ID = :partner_id');
                $agent_invoice->bindValue(':ref_id', $TRANSACTIONID);
                $agent_invoice->bindValue(':partner_id', $partner_id);
                $invoice = $agent_invoice->queryOne();
                if (!empty($invoice)) {
                    $update_order_query = "UPDATE tbl_order SET RECEIVED_AMOUNT = :received_amount, PAYMENT_STATUS = :payment_status, TRANSACTION_ID = :transaction_id, TRANSACTION_STATUS = :transaction_status, TRANSACTION_MESSAGE = :transaction_message , CREATED_ON  = :created_on WHERE INVOICE_ID = :invoice_id";
                    $update_order = $connection->createCommand($update_order_query);
                    $update_order->bindValue(':invoice_id', $invoice['INVOICE_ID']);
                    $update_order->bindValue(':received_amount', $AMOUNT);
                    $update_order->bindValue(':payment_status', 1);
                    $update_order->bindValue(':transaction_id', $APTRANSACTIONID);
                    $update_order->bindValue(':transaction_status', $TRANSACTIONSTATUS);
                    $update_order->bindValue(':transaction_message', $MESSAGE);
                    $update_order->bindValue(':created_on', strtotime($TRANSACTIONTIME));
                    $update_order->execute();
                    $update_invoice_query = "UPDATE tbl_invoice SET INVOICE_STATUS = 1 WHERE INVOICE_ID = :invoice_id";
                    $update_invoice = $connection->createCommand($update_invoice_query);
                    $update_invoice->bindValue(':invoice_id', $invoice['INVOICE_ID']);
                    $update_invoice->execute();
                    $is_payment_done = true;
                }
            }
        }


        $get_agent_details = $connection->createCommand('SELECT A.*,B.* FROM tbl_agent_details as A INNER JOIN tbl_partner_master as B ON A.PARTNER_ID = B.PARTNER_ID WHERE A.PARTNER_ID=:partner_id');
        $get_agent_details->bindValue(':partner_id', $partner_id);
        $get_agency_data = $get_agent_details->queryOne();
        $agent_details_id = $get_agency_data['AGENT_DETAILS_ID'];


        $get_agent_cards = $connection->createCommand('SELECT A.* FROM tbl_agent_payment_config as A WHERE A.AGENT_DETAILS_ID =:agent_details_id');
        $get_agent_cards->bindValue(':agent_details_id', $agent_details_id);
        $cards = $get_agent_cards->queryAll();


        $get_banks = $connection->createCommand('SELECT * FROM tbl_bank');
        $banks = $get_banks->queryAll();

        $group_limit = $connection->createCommand('SELECT A.* FROM tbl_agent_group_payment_limit as A WHERE A.GROUP_ID =:group_id');
        $group_limit->bindValue(':group_id',$get_agency_data['AGENT_GROUP_ID']);
        $group_limit_info = $group_limit->queryOne();


        $transactions_query = "SELECT A.*,B.* FROM tbl_invoice as A INNER JOIN tbl_order as B ON A.INVOICE_ID = B.INVOICE_ID WHERE PARTNER_ID = :partner_id AND A.AGENT_ID  =:agent_id ORDER BY A.CREATED_ON DESC";
        $get_transactions = $connection->createCommand($transactions_query);
        $get_transactions->bindValue(':partner_id', $partner_id);
        $get_transactions->bindValue(':agent_id', $agent_details_id);
        $transactions = $get_transactions->queryAll();


        $do_payment_url = \yii\helpers\Url::to(['/spicejet/action/dopayment']);
        $add_cards_url = \yii\helpers\Url::to(['/spicejet/action/addcards']);
        $add_banks_url = \yii\helpers\Url::to(['/spicejet/action/addbanks']);
        return $this->render('index', [
            'get_agency_data' => $get_agency_data,
            'cards' => $cards,
            'banks' => $banks,
            'do_payment_url' => $do_payment_url,
            'add_cards_url' => $add_cards_url,
            'add_banks_url' => $add_banks_url,
            'partner_id' => $partner_id,
            'agent_id' => $agent_details_id,
            'is_payment_done' => $is_payment_done,
            'transactions' => $transactions,
            'group_info' => $group_limit_info,
        ]);
    }

    public function actionGetcards()
    {
        $agent_id = isset($_POST['agent_id']) ? trim($_POST['agent_id']) : '';
        $connection = \Yii::$app->db;
        $get_agent_cards = $connection->createCommand('SELECT A.* FROM tbl_agent_payment_config as A WHERE A.AGENT_DETAILS_ID =:agent_id');
        $get_agent_cards->bindValue(':agent_id', $agent_id);
        $cards = $get_agent_cards->queryAll();

        $card_html = '<table class="table table-bordered text-center "><thead><tr><th class="text-center idnum">#</th><th class="text-center">Payment Instrument</th><th class="text-center">Payment Type</th></tr></thead><tbody>';
        if (!empty($cards)) {
            $i = 0;
            foreach ($cards as $card) {
                $i++;
                if (empty($card['BANK_ID'])) {
                    $card_html .= '<tr>
                                        <td class="idnum">' . $i . '</td>
                                        <td>' . $card['CARD_NUMBER'] . '</td>
                                        <td>' . $card['CARD_TYPE'] . '</td>
                                        </tr>';
                } else {
                    $bank_id = $card['BANK_ID'];
                    $get_agent_bank = $connection->createCommand('SELECT A.* FROM tbl_bank as A WHERE A.BANK_ID =:bank_id');
                    $get_agent_bank->bindValue(':bank_id', $bank_id);
                    $agent_bank = $get_agent_bank->queryOne();
                    $card_html .= '<tr>
                                           <td class="idnum">' . $i . '</td>
                                           <td>' . $agent_bank['BANK_NAME'] . '</td>
                                           <td>Netbanking</td>
                                           </tr>';

                }
            }
        } else {
            $card_html .= '<tr rowspan="3"><td>No records.</td></tr>';
        }
        $card_html .= '</tbody></table>';
        echo $card_html;
    }

    public function actionGetpaymentoptions()
    {
        $agent_id = isset($_POST['agent_id']) ? trim($_POST['agent_id']) : '';
        $connection = \Yii::$app->db;
        $get_agent_cards = $connection->createCommand('SELECT A.* FROM tbl_agent_payment_config as A WHERE A.AGENT_DETAILS_ID =:agent_id');
        $get_agent_cards->bindValue(':agent_id', $agent_id);
        $cards = $get_agent_cards->queryAll();

        $card_html = '<table class="table table-bordered">';
        if (!empty($cards)) {
            $i = 0;
            foreach ($cards as $card) {
                $i++;
                if (empty($card['BANK_ID'])) {
                    $card_html .= '<tr>
                                        <td><label><input type="radio" name="cards" id="cards_' . $i . '" value="' . $card["AGENT_PAYMENT_CONFIG_ID"] . '"> ' . $card["CARD_NUMBER"] . '</label></td>
                                        <td>' . $card["CARD_NUMBER"] . '</td>
                                        <td>';


                    if (isset($card['STATUS']) && $card['STATUS'] != '') {
                        if ($card['STATUS'] == '0') {
                            $status = 'PROCESSING';
                        } else if ($card['STATUS'] == '1') {
                            $status = 'APPROVED';
                        } else if ($card['STATUS'] == '2') {
                            $status = 'REJECTED';
                        }
                    } else {
                        $status = '';
                    }
                    $card_html .= $status;
                    $card_html .= '</td>
                                                <td class="action">
                                                    <div class="bbox">
                                                        <a href="#" title="Update agent details"><span class="glyphicon glyphicon-pencil"></span></a>
                                                        <a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a>
                                                    </div>
                                                </td>
                                            </tr>';
                } else {
                    $bank_id = $card['BANK_ID'];
                    $get_agent_bank = $connection->createCommand('SELECT A.* FROM tbl_bank as A WHERE A.BANK_ID =:bank_id');
                    $get_agent_bank->bindValue(':bank_id', $bank_id);
                    $agent_bank = $get_agent_bank->queryOne();
                    $card_html .= '<tr><td colspan="3"><label><input type="radio" name="cards" id="cards_'.$i.'" value="'.$card["AGENT_PAYMENT_CONFIG_ID"].'"> '.$agent_bank["BANK_NAME"].'</label></td>
                                                    <td class="action">
                                                        <div class="bbox">
                                                            <a href="#" title="Update agent details"><span class="glyphicon glyphicon-pencil"></span></a>
                                                            <a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>';

                }
            }
        } else {
            $card_html .= '<tr rowspan="4"><td>No records.</td></tr>';
        }
        $card_html .= '</tbody></table>';
        echo $card_html;
    }
}

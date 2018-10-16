<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 19/2/16
 * Time: 3:14 PM
 */
?>

<?php if (!$status) { ?>
    <div class="response_pagelog">
        <i class="fa fa-times fa-4x"></i>
        <h2>Sorry</h2>
        <?php   echo "<p> ".$response_message."</p>"; ?>
    </div>
    <?php
} else { ?>
    <div class="invoiceprint">
        <table class="table">
            <tbody>
            <tr class="first"><th></th>
                <td>
                    <a href="#" onclick="javascript: window.print(); return false;" class="printbtn">
                        <img alt="print-icon" src="<?php echo $theme->baseUrl; ?>/images/print-icon.png"/>
                    </a>
                </td>
            </tr>
            <tr class="second">
                <th>Date :</th>
                <td>
                    <?php echo date('Y, M d', $order->CREATED_ON) ?>
                </td>
            </tr>
            <tr>
                <th>Received with thanks from :</th>
                <td>
                    <?php
                    echo !empty($client) ? $client->FIRST_NAME . ' ' . $client->LAST_NAME : null;
                    ?>
                </td>
            </tr>
            <tr><th>The sum of Rupees :</th>
                <td>
                    <?php
                    setlocale(LC_MONETARY, 'en_IN');
                    //$amount = money_format('%!i', $order->RECEIVED_AMOUNT);
                    $amount = $order->RECEIVED_AMOUNT;
                    echo $amount;
                    ?>
                </td>
            </tr>
            <tr><th>In FULL payment against:</th>
                <td>
                    <?php echo $invoice->GROUP_INVOICE_ID; ?>
                </td>
            </tr>
            <tr><th>On Dated :</th>
                <td>
                    <?php echo date('Y, M d', $order->CREATED_ON) ?>
                </td>
            </tr>
            <tr><th>Transaction Reference Number :</th>
                <td>
                    <?php echo $order->TRANSACTION_ID; ?>
                </td>
            </tr>
            <tr class="last">
                <th></th>
                <td>Thank You</td>
            </tr>
            </tbody>
        </table>

    </div>
<?php } ?>

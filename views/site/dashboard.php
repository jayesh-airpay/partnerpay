<?php
/**
 * Created by PhpStorm.
 * User: nandana
 * Date: 29/2/16
 * Time: 7:08 PM
 */
?>

        <div class="page-header">
            <h4>Dashboard</h4>
        </div>

         <div class="row">


                <div class="col-md-3">
                    <div class="total-countbox">
                        <div class="total-counttx"><h3><span>Total</span> Invoices</h3></div>
                        <div class="total-count"><?= $total_count ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="total-countbox">
                        <div class="total-counttx"><h3><span>Total</span> Paid invoices </h3></div>
                        <div class="total-count"><?= $paid_count ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="total-countbox">
                        <div class="total-counttx"><h3><span>Total</span> unpaid invoices </h3></div>
                        <div class="total-count"><?= $unpaid_count ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="total-countbox">
                        <div class="total-counttx"><h3><span>Total</span> Approved invoices </h3></div>
                        <div class="total-count"><?= $approved_count ?></div>
                    </div>
                </div>


        </div>
 
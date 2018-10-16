<?php 
use yii\helpers\ArrayHelper;

$cat = \app\models\CategoryMaster::find()->all();
$listData = ArrayHelper::map($cat, 'CAT_ID', 'CAT_NAME');

$this->registerJs('
    qr = \'<option value="Submitted">Submitted</option><option value="Processing">Processing</option><option value="Executed">Executed</option><option value="Expired">Expired</option><option value="Failed">Failed</option>\';
    po = \'<option value="A">Accepted</option><option value="R">Rejected</option>\';
    invoice = \'<option value="0">Pending</option><option value="1">Paid</option>\';
    
    $("#qpi").change(function(){
       if(this.value == 2){
          $("#status").html(qr);
       }else if(this.value == 3){
          $("#status").html(po);
       }else if(this.value == 4){
         $("#status").html(invoice);
       }else{
         $("#status").html(\'<option value="">select</option>\');
       }
    });

');

?>      
<div class="page-header">
                <h4 class="pull-left">Dashboard</h4>
                <div class="clearfix"></div>
            </div>
            <div class="dashselect">
                <div class="row">
                    <div class="col-md-4">
                        <div class="total-countbox" data-tab="tabs-1">
                            <div class="total-counttx">
                                <h3><span>Total</span> QR </h3>
                            </div>
                            <div class="total-count"><?php echo !empty($qr_count)?$qr_count:'0';?>
                                <div class="total-rs">( &#8377; <?php echo !empty($qr_amount)?$qr_amount:'0';?> )</div>
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="total-countbox" data-tab="tabs-2">
                            <div class="total-counttx">
                                <h3><span>Total</span> PO </h3>
                            </div>
                            <div class="total-count"><?php echo !empty($po_count)?$po_count:'0'; ?>
                                <div class="total-rs">( &#8377; <?php echo !empty($po_amount)?$po_amount:'0';?> )</div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="total-countbox" data-tab="tabs-3">
                            <div class="total-counttx">
                                <h3><span>Total</span> Invoices </h3>
                            </div>
                            <div class="total-count"><?php echo !empty($inv_count)?$inv_count:'0';?>
                                <div class="total-rs">( &#8377; <?php echo !empty($inv_amount)?$inv_count:'0';?> )</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row qrbox main-box" data="tabs-1">
                <div class="col-sm-3 sub">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontGR"><span>Total</span> Submitted </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($qr_submitted)?$qr_submitted:'0';?></div>
                    </div>
                </div>
                <div class="col-sm-3 pro">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontOR"><span>Total</span> Processing </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($qr_processing)?$qr_processing:'0';?></div>
                    </div>
                </div>
                <div class="col-sm-3 rej">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontRD"><span>Total</span> Rejected </h3>
                        </div>
                        <div class="total-count">0</div>
                    </div>
                </div>
                <div class="col-sm-3 exp">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontBL"><span>Total</span> Expired </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($qr_expired)?$qr_expired:'0';?></div>
                    </div>
                </div>
            </div>
            <div class="row pobox main-box" data="tabs-2">
                <div class="col-sm-3 sub">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontGR"><span>Total</span> Approved </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($po_approved)?$po_approved:'0';?></div>
                    </div>
                </div>
                <div class="col-sm-3 pro">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontOR"><span>Total</span> Pending </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($po_pending)?$po_pending:'0';?></div>
                    </div>
                </div>
                <div class="col-sm-3 rej">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontRD"><span>Total</span> Rejected </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($po_rejected)?$po_rejected:'0';?></div>
                    </div>
                </div>
            </div>
            <div class="row invbox main-box" data="tabs-3">
                <div class="col-sm-3 sub">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontGR"><span>Total</span> Paid </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($inv_paid)?$inv_paid:'0';?></div>
                    </div>
                </div>
                <div class="col-sm-3 rej">
                    <div class="total-countbox">
                        <div class="total-counttx">
                            <h3 class="fontRD"><span>Total</span> Unpaid </h3>
                        </div>
                        <div class="total-count"><?php echo !empty($inv_pending)?$inv_pending:'0';?></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="drep" data-toggle="collapse" data-target="#reports-search-content" aria-expanded="true" aria-controls="reports-search-content" aria-hidden="true">
                <a href="javascript:void(0);">Download Reports</a>
            </div>
            <div class="expandfilter filter-collapsed collapse" id="reports-search-content" aria-expanded="true">
                <div class="reportwrapper">
                    <div class="row">
                        <form action="/invoice/downloadreport" method="post">
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="qpi" id="qpi" required>
                                <option value="">Select</option>
                                <option value="1">All</option>
                                <option value="2">QR</option>
                                <option value="3">PO</option>
                                <option value="4">Invoice</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select id="" class="form-control multiplebox" name="cat[]" id="cat" multiple="multiple" data-placeholder="Select Category">
                                   <?php foreach($listData as $k => $v)  {   echo '<option value="'.$k.'">'.$v.'</option>';     }?>
                                 
                                </select>
				            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="status" id="status">       
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="btnlink pull-left"><button type="submit" class="btn btn-primary">Submit</button></div>
                            <a data-mode="excel" href="javascript:void(0);" class="excel-icon pull-right downloadExcel" title="Download Excel"><img width="30px" alt="Download Excel" src="/themes/partnerpay/images/excel-icon.gif"></a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
  
<?php
/* @var $this yii\web\View */
?>
<link rel="shortcut icon" type="image/x-icon" href="resources/images/favicon.ico">
<link rel="stylesheet" href="resources/css/bootstrap.css" type="text/css"/>
<link rel="stylesheet" href="resources/css/font-awesome.css" type="text/css"/>
<link rel="stylesheet" href="resources/css/custom.css" type="text/css"/>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<div class="page-header">
    <h4>Agency Details</h4>
</div>

<div class="agencyinfo">
    <ul>
        <li class="th">Agency Name</li>
        <li><?php echo !empty($get_agency_data['COMPANY_NAME']) ? $get_agency_data['COMPANY_NAME'] : ''; ?></li>
    </ul>
    <ul>
        <li class="th">Agent ID</li>
        <li><?php echo !empty($get_agency_data['AGENT_ID']) ? $get_agency_data['AGENT_ID'] : ''; ?></li>
    </ul>
    <ul>
        <li class="th">Email</li>
        <li><?php echo !empty($get_agency_data['EMAIL']) ? $get_agency_data['EMAIL'] : ''; ?></li>
    </ul>
    <ul>
        <li class="th">Mobile</li>
        <li><?php echo !empty($get_agency_data['PHONE']) ? $get_agency_data['PHONE'] : ''; ?></li>
    </ul>
</div>

<div class="agencypay">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#payopc" aria-controls="home" role="tab" data-toggle="tab">Make Payment</a></li>
        <li><a href="#newpay" aria-controls="newpay" role="tab" data-toggle="tab">Add New Payment Method</a></li>
        <li><a href="#trans" aria-controls="trans" role="tab" data-toggle="tab">View Transaction</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <?php
        if($is_payment_done) {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <div id="msg"><p>Payment done successfully.</p></div>
                    </div>
                </div>
            </div>
        <?php
        } else {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible" style="display: none">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <div id="msg"></div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

        <div role="tabpanel" class="tab-pane active" id="payopc">
            <form id="payment-form" method="post" action="<?php echo \yii\helpers\Url::to(['/spicejet/action/sendtoairpay']); ?>">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" id="partner_id" name="partner_id" value=""/>
                <input type="hidden" id="agent_payment_config_id" name="agent_payment_config_id" value=""/>
                <input type="hidden" id="transaction_amount" name="transaction_amount" value=""/>
                <input type="hidden" id="payment_form_card_cvv" name="card_cvv" value=""/>
                <div class="row listpanel">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <p>*Minimum upload Amount for credit card is Rs. <?php echo !empty($group_info['CREDIT_CARD_LOWER_LIMIT']) ? $group_info['CREDIT_CARD_LOWER_LIMIT'] : '0.00'; ?><br>*Minimum upload Amount for Debit card is Rs. <?php echo !empty($group_info['DEBIT_CARD_LIMIT']) ? $group_info['DEBIT_CARD_LIMIT'] : '0.00'; ?><br>*Minimum upload Amount for net banking is
                            Rs. <?php echo !empty($group_info['NETBANKING_LIMIT']) ? $group_info['NETBANKING_LIMIT'] : '0.00'; ?></p>
                    </div>
                </div>
                <div class="row listpanel">
                    <div class="col-md-2">Amount</div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Rs</span>
                            <input id="amount" type="text" class="form-control" placeholder="Enter Amount here...">
                        </div>
                        <div class="help-block" id="amount_error"></div>
                    </div>
                </div>
                <div class="row listpanel">
                    <div class="col-md-2">Pay Using</div>
                    <div class="col-md-8">
                        <div class="table-responsive listveiw pay-using">
                            <table class="table table-bordered">
                                <?php
                                if (!empty($cards)) {
                                    $i = 0;
                                    foreach ($cards as $card) {
                                        $i++;
                                        if (empty($card['BANK_ID'])) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <label><input type="radio" name="cards" id="cards_<?php echo $i; ?>" value="<?php echo $card['AGENT_PAYMENT_CONFIG_ID']; ?>"> <?php echo $card['CARD_NUMBER']; ?></label>
                                                </td>
                                                <td>
                                                    <?php echo $card['CARD_TYPE']; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if (isset($card['STATUS']) && $card['STATUS'] != '') {
                                                        if ($card['STATUS'] == '0') {
                                                            echo 'PROCESSING';
                                                        } else if ($card['STATUS'] == '1') {
                                                            echo 'APPROVED';
                                                        } else if ($card['STATUS'] == '2') {
                                                            echo 'REJECTED';
                                                        }
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="action">
                                                    <div class="bbox">
                                                        <a href="#" title="Update agent details"><span class="glyphicon glyphicon-pencil"></span></a>
                                                        <a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        } else { ?>
                                            <?php
                                            $connection = \Yii::$app->db;
                                            $bank_id = $card['BANK_ID'];
                                            $get_agent_banks = $connection->createCommand('SELECT A.* FROM tbl_bank as A WHERE A.BANK_ID =:bank_id');
                                            $get_agent_banks->bindValue(':bank_id', $bank_id);
                                            $agent_banks = $get_agent_banks->queryOne();
                                            if (!empty($agent_banks)) {
                                                ?>
                                                <tr>
                                                    <td colspan="3">
                                                        <label><input type="radio" name="cards" id="cards_<?php echo $i; ?>" value="<?php echo $card['AGENT_PAYMENT_CONFIG_ID']; ?>"> <?php echo $agent_banks['BANK_NAME']; ?></label>
                                                    </td>
                                                    <td class="action">
                                                        <div class="bbox">
                                                            <a href="#" title="Update agent details"><span class="glyphicon glyphicon-pencil"></span></a>
                                                            <a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                } ?>
                            </table>
                        </div>
                        <div class="help-block" id="submit_form_payment_method_error"></div>
                    </div>
                </div>
                <div class="row listpanel">
                    <div class="col-md-2">Card CVV</div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input id="card_cvv" type="text" class="form-control" placeholder="Enter cvv here...">
                        </div>
                        <div class="help-block" id="card_cvv_error"></div>
                    </div>
                </div>
                <div class="row listpanel">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="button" class="btn btn-primary" id="submitform" value="Pay">
                        </div>
                    </div>
                </div>
        </div><!--close payopc-->
        <div role="tabpanel" class="tab-pane" id="newpay">
            <h5>Payment Method</h5>
            <div class="row">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <div class="col-sm-6 col-md-5 col-lg-4">
                    <div class="form-group">
                        <label class="radio-inline">
                            <input type="radio" name="payment_card_type" id="ccard" value="CREDIT" checked>Credit Card
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="payment_card_type" id="dcard" value="DEBIT"> Debit Card
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="payment_card_type" id="banking" value="NETBANKING"> Netbanking
                        </label>
                        <div id="payment_card_type_error" class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="cardtype">
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <div class="form-group">
                            <select class="form-control" id="card_type" name="card_type">
                                <option value="">Select Card Type</option>
                                <option value="International">International</option>
                                <option value="Domestic">Domestic</option>
                            </select>
                            <div id="card_type_error" class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <div class="form-group req">
                            <input type="text" id="card_number" name="card_number" class="form-control" placeholder="Card Number" maxlength="19"    >
                            <div id="card_number_error" class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <div class="form-group req">
                            <input type="text" id="card_nickname" name="card_nickname" class="form-control" placeholder="Card Nick Name">
                            <div id="card_nickname_error" class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group req">
                                    <select id="expiry_month" name="expiry_month" class="form-control">
                                        <option value="">Expiry Month</option>
                                        <?php
                                        for($i=1;$i<13;$i++)
                                        {
                                        ?>
                                            <option value = "<?php echo sprintf("%02d", $i);?>" > <?php echo sprintf("%02d", $i);?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <div id="expiry_month_error" class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group req">
                                    <select class="form-control" id="expiry_year">
                                        <option value="">Expiry Year</option>
                                        <?php
                                        for($i=1;$i<21;$i++)
                                        {
                                            ?>
                                            <option value = "<?php echo (date('Y')+$i);?>" > <?php echo (date('Y')+$i);?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <div id="expiry_year_error" class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="button" class="btn btn-primary" id="addcardbtn" value="Add Card">
                </div>
            </div>
            <div class="banktype" style="display:none;">
                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-4">
                        <div class="form-group">
                            <select id="select_bank" class="form-control">
                                <option value="">Select Bank</option>
                                <?php foreach ($banks as $bank) { ?>
                                    <option value="<?php echo $bank['BANK_ID']; ?>"><?php echo $bank['BANK_NAME']; ?></option>
                                <?php } ?>
                            </select>
                            <div id="select_bank_error" class="help-block"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="button" class="btn btn-primary" id="addbankbtn" value="Add Bank">
                </div>
            </div>


            <div class="tablecard">
                <div class="table-responsive listveiw cardlist">
                    
                </div>
                <div class="form-group">
                    <input type="button" class="btn btn-primary" id="addpayMethod" value="Approve Method">
                </div>
            </div>
            </form>
        </div><!--close newpay-->

        <div role="tabpanel" class="tab-pane" id="trans">
            <div class="page-header">
                <h5 class="pageth">View Transaction</h5>
                <?php $export_link = '/spicejet/action/exporttransactions?partner_id='.$partner_id.'&agent_id='.$agent_id;?>
                <div class="fieldstx"><i class="icon i-excel"><a href="<?php echo \yii\helpers\Url::to([$export_link]); ?>"><img src="resources/images/excel-icon.gif"></a></i></div>
            </div>

            <div class="table-responsive listveiw">
                <table class="table table-bordered text-center ">
                    <thead>
                    <tr>
                        <th class="text-center idnum">#</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Transaction Id</th>
                        <th class="text-center">Transaction Type</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">PG/Bank Name</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    //if(false) {
                    if(!empty($transactions)) {
                        $i = 0;
                        foreach($transactions as $transaction) {
                            $i++;
                    ?>
                            <tr>
                                <td class="idnum"><?php echo $i;?></td>
                                <td><?php echo date("d-m-Y",$transaction['CREATED_ON']);?></td>
                                <td><?php echo !empty($transaction['REF_ID'])?$transaction['REF_ID']:''?></td>
                                <td>Sale</td>
                                <td><?php echo !empty($transaction['TRANSACTION_MESSAGE'])?$transaction['TRANSACTION_MESSAGE']:''?></td>
                                <td>INR <?php echo !empty($transaction['RECEIVED_AMOUNT'])?$transaction['RECEIVED_AMOUNT']:''?></td>
                                <td>ICICI Bank PG</td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="7" align="left">No records.</td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div><!--close trans-->
    </div>
</div>
<div class="footer">
    <div class="container copyright"><p>Copyright © 2018 by airpay.</p></div>
</div>
<!-- jQuery -->
<script type="text/javascript" src="resources/js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script type="text/javascript" src="resources/js/bootstrap.min.js"></script>
<script type="text/javascript" src="resources/js/bootstrap.file-input.js"></script>
<script type="text/javascript" src="resources/js/custom.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var is_payment_done = "<?php echo $is_payment_done;?>";
        console.log(is_payment_done);
        if(is_payment_done) {
            setTimeout(function(){
                $("#msg").html("");
                $(".alert").hide();
            }, 3000);
        }

        $("#addbankbtn").click(function () {
            $(".alert").hide();
            $("#msg").html("");
            $(".help-block").hide();
            $(".help-block").html("");
            var rt_type      =        true;
            var payment_type =        $("#banking").val();
            var bank         =        $("#select_bank").val();
            var add_bank_partner_id   =        "<?php echo $partner_id;?>";
            if(payment_type == '') {
                $(".help-block").show();
                $("#payment_card_type_error").html("Please select payment type.");
                rt_type = false;
            }
            if(bank == '') {
                $(".help-block").show();
                $("#select_bank_error").html("Please select bank.");
                rt_type = false;
            }
            if(rt_type) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $add_banks_url;?>",
                    data:
                    "payment_type=" + payment_type + "&bank=" + bank+"&add_bank_partner_id="+add_bank_partner_id,
                }).done(function (resp) {
                    var msg = '';
                    resp_object = JSON.parse(resp);
                    console.log(resp_object);
                    if(resp_object.Result == 'Success') {
                        $('.tablecard').show();
                        var agent_id = "<?php echo $agent_id;?>";
                        $.ajax({
                            url: "/partnerpay/web/spicejet/agency/getcards",  
                            data: "agent_id=" + agent_id,
                            type: "POST",
                            success: function(resp) {
                                console.log(resp);
                                $(".cardlist").html("");
                                $(".cardlist").html(resp);
                            }
                        });
                        $.ajax({
                            url: "/partnerpay/web/spicejet/agency/getpaymentoptions",
                            data: "agent_id=" + agent_id,
                            type: "POST",
                            success: function(resp) {
                                console.log(resp);
                                $(".pay-using").html("");
                                $(".pay-using").html(resp);
                            }
                        });


                        $("#select_bank").val("");
                        $('input:radio[name=payment_card_type]:checked').prop('checked', false);
                        $(".alert").removeClass('alert-danger');
                        $(".alert").addClass('alert-success');
                        $(".alert").show();
                        $("#msg").html("");
                        $("#msg").html("Card added successfully.");
                        setTimeout(function(){
                            $("#msg").html("");
                            $(".alert").hide();
                            //location.reload();
                        }, 3000);
                    } else {
                        $(".alert").removeClass('alert-success');
                        $(".alert").addClass('alert-danger');
                        $(".alert").show();
                        console.log(resp_object.Message);
                        $.each(resp_object.Message, function (index, item) {
                            msg += '<p>' + item + '</p>';
                        });
                        console.log(msg);
                        $("#msg").html("");
                        $("#msg").html(msg);
                        return false;
                    }
                    return false;
                });
            } else {
                return fase;
            }



        });

        $("#addcardbtn").click(function () {
            $(".alert").hide();
            $("#msg").html("");
            $(".help-block").hide();
            $(".help-block").html("");
            var rt_type            =        true;
            if ($("input[name='payment_card_type']:checked").val() != undefined) {
                var payment_card_type = $("input[name='payment_card_type']:checked").val().trim();
            }
            var card_type          =        $("#card_type").val();
            var card_number        =        $("#card_number").val();
            var card_nickname      =        $("#card_nickname").val();
            var expiry_month       =        $("#expiry_month").val();
            var expiry_year        =        $("#expiry_year").val();
            var partner_id         =        "<?php echo $partner_id;?>";
            if(payment_card_type == '') {
                $(".help-block").show();
                $("#payment_card_type_error").html("Please select payment card type.");
                rt_type = false;
            }
            if(card_type == '') {
                $(".help-block").show();
                $("#card_type_error").html("Please select card type.");
                rt_type = false;
            }
            if(card_number == '') {
                $(".help-block").show();
                $("#card_number_error").html("Please enter card number.");
                rt_type = false;
            } else {
                if($("#card_number").val().length > '16') {
                    $(".help-block").show();
                    $("#card_number_error").html("Invalid card number.");
                    rt_type = false;
                }
                var t = card_number.split(" ").join("");
                var n = /^\d+$/;
                if (!t.match(n)) {
                    $(".help-block").show();
                    $("#card_number_error").html("Please enter card number.");
                    rt_type = false;
                } else {
                    var r = GetCardType(t);
                    if (typeof r === "undefined") {
                        $(".help-block").show();
                        $("#card_number_error").html("Invalid card number.");
                        rt_type = false;
                    } else {
                        var i = r.split("~");
                        var s = i[0];
                        var o = i[1];
                        var u;
                        if (s == "Visa") {
                            u = new RegExp("^4")
                        } else if (s == "American Express") {
                            u = new RegExp("^(34|37)")
                        } else if (s == "MasterCard") {
                            u = new RegExp("^5[1-5]")
                        } else if (s == "Discover") {
                            u = new RegExp("^6011")
                        }else if (s == "Rupay") {
                            u = new RegExp("^(508|606|607|608|652|653)")
                        }
                        if (t.match(u) != null) {
                            if (t.length != 16) {
                                $(".help-block").show();
                                $("#card_number_error").html("Card Number should be 16 digits.");
                                rt_type = false;
                            }
                        }
                    }
                }
            }
            if(card_nickname == '') {
                $(".help-block").show();
                $("#card_nickname_error").html("Please select card nickname.");
                rt_type = false;
            } else {
                var reg = /^[A-Za-z\d\s]+$/;
                if (!reg.test(card_nickname)){
                    $(".help-block").show();
                    $("#card_nickname_error").html('Please enter valid card nickname.');
                    rt_type = false;
                }
                else
                {
                    if(card_nickname.length<1)
                    {
                        $(".help-block").show();
                        $("#card_nickname_error").html('Card nickname should be minimum 1 character.');
                        rt_type = false;
                    }
                }
            }
            if(expiry_month == '') {
                $(".help-block").show();
                $("#expiry_month_error").html("Please select expiry month.");
                rt_type = false;
            } else {
                if (parseInt(expiry_month) < parseInt(1) || parseInt(expiry_month) > parseInt(12)) {
                    $(".help-block").show();
                    $("#expiry_month_error").html("Invalid expiry month.");
                    rt_type = false;
                }
            }
            if(expiry_year == '') {
                $(".help-block").show();
                $("#expiry_year_error").html("Please select expiry year.");
                rt_type = false;
            }
            if(rt_type) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $add_cards_url;?>",
                    data:
                    "payment_card_type=" + payment_card_type + "&card_type=" + card_type + "&card_number=" + card_number+ "&card_nickname=" + card_nickname+ "&expiry_month=" + expiry_month + "&expiry_year=" + expiry_year+"&add_card_partner_id="+partner_id,
                }).done(function (resp) {
                    var msg = '';
                    resp_object = JSON.parse(resp);
                    console.log(resp_object);
                    if(resp_object.Result == 'Success') {
                        $('.tablecard').show();
                        var agent_id = "<?php echo $agent_id;?>";
                        $.ajax({
                            url: "/partnerpay/web/spicejet/agency/getcards",  
                            data: "agent_id=" + agent_id,
                            type: "POST",
                            success: function(resp) {
                                console.log(resp);
                                $(".cardlist").html("");
                                $(".cardlist").html(resp);
                            }
                        });
                        $.ajax({
                            url: "/partnerpay/web/spicejet/agency/getpaymentoptions",
                            data: "agent_id=" + agent_id,
                            type: "POST",
                            success: function(resp) {
                                console.log(resp);
                                $(".pay-using").html("");
                                $(".pay-using").html(resp);
                            }
                        });
                        $('input:radio[name=payment_card_type]:checked').prop('checked', false);
                        $("#card_type").val("");
                        $("#card_number").val("");
                        $("#card_nickname").val("");
                        $("#expiry_month").val("");
                        $("#expiry_year").val("");
                        $(".alert").removeClass('alert-danger');
                        $(".alert").addClass('alert-success');
                        $(".alert").show();
                        $("#msg").html("");
                        $("#msg").html("Card added successfully.");
                        setTimeout(function(){
                            $("#msg").html("");
                            $(".alert").hide();
                            //location.reload();
                        }, 3000);
                    } else {
                        $(".alert").removeClass('alert-success');
                        $(".alert").addClass('alert-danger');
                        $(".alert").show();
                        console.log(resp_object.Message);
                        $.each(resp_object.Message, function (index, item) {
                            msg += '<p>' + item + '</p>';
                        });
                        console.log(msg);
                        $("#msg").html("");
                        $("#msg").html(msg);
                        return false;
                    }
                });
            } else {
                return false;
            }
        });

        $("#submitform").click(function () {
            $("#submit_form_payment_method_error").html("");
            $("#amount_error").html("");
            $("#card_cvv_error").html("");
            var rt_type            =        true;
            var amount             =        $("#amount").val().trim();
            var card_cvv           =        $("#card_cvv").val().trim();
            if ($("input[name='cards']:checked").val() != undefined) {
                var agent_payment_config_id     =       $("input[name='cards']:checked").val().trim();
            }
            var partner_id                      =       "<?php echo $partner_id;?>";
            if (amount == '') {
                $("#amount_error").html("Please enter amount.");
                rt_type                         =       false;
            }
            if (!amount.match(/^(\d{1,6})(\.\d{2})$/)) {
                $("#amount_error").html("Please enter valid amount.");
                rt_type                         =       false;
            }
            if (agent_payment_config_id == undefined) {
                $("#submit_form_payment_method_error").html("Please select payment card.");
                rt_type                         =       false;
            }
            if ($('input:radio[name="cards"]:checked').length == 0) {
                $("#submit_form_payment_method_error").html("Please select payment card.");
                rt_type                         =       false;
            }
            if(card_cvv == '') {
                $("#card_cvv_error").html("Please enter card cvv.");
                rt_type                         =       false;
            } else {
                /*var t = card_cvv.split(" ").join("");
                var n = /^\d+$/;
                if (!card_cvv.match(n)) {
                    $("#card_cvv_error").html("Invalid card cvv.");
                    return false;
                } else {
                    var r = GetCardType(t);
                    if (typeof r === "undefined") {
                        $("#card_cvv_error").html("Invalid card cvv.");
                        rt_type = false;
                    } else {
                        var i = r.split("~");
                        var s = i[0];
                        if (s == "MasterCard" || s == "Visa") {
                            if (card_cvv.length != 3) {
                                $("#card_cvv_error").html("CVV number should be 3 digits.");
                                rt_type = false;
                            }
                        }
                    }
                }*/
            }

            if(rt_type) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $do_payment_url;?>",
                    data:
                    "amount=" + amount + "&agent_payment_config_id=" + agent_payment_config_id + "&partner_id=" + partner_id,
                }).done(function (resp) {
                    var msg = '';
                    resp_object = JSON.parse(resp);
                    console.log(resp_object);
                    $("#partner_id").val(partner_id);
                    $("#agent_payment_config_id").val(agent_payment_config_id);
                    $("#transaction_amount").val(amount);
                    $("#payment_form_card_cvv").val(card_cvv);
                    if (resp_object.Result == 'Success') {
                        $("#payment-form").submit();
                    } else {
                        $(".alert").removeClass('alert-success');
                        $(".alert").addClass('alert-danger');
                        $(".alert").show();
                        console.log(resp_object.Message);
                        $.each(resp_object.Message, function (index, item) {
                            msg += '<p>' + item + '</p>';
                        });
                        console.log(msg);
                        $("#msg").html("");
                        $("#msg").html(msg);
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
    });
    $("#addpayMethod").click(function () {
        window.setTimeout(function () {
            window.location.href = 'admin-login.html';
        }, 500);
    });

    $('#addcardbtn').click(function () {
        

    });

    $('#addbankbtn').click(function () {
        
    });

    $('input[name="payment_card_type"]').change(function () {
        if ($('#banking').prop('checked')) {
            $('.cardtype').hide();
            $('.banktype').show();
        } else {
            $('.cardtype').show();
            $('.banktype').hide();
        }
    });

    function GetCardType(e) {
        var t = new RegExp("^4");
        if (e.match(t) != null) return "Visa~16";
        /*if (document.ccfrm.amexvalidation.value == "Y") {
            t = new RegExp("^(34|37)");
            if (e.match(t) != null) return "American Express~15"
        }*/
        t = new RegExp("^5[1-5]");
        if (e.match(t) != null) return "MasterCard~16";
        t = new RegExp("^6011");
        if (e.match(t) != null) return "Discover~16";
        t = new RegExp("^(508|606|607|608|652|653)");
        if (e.match(t) != null) return "Rupay~16"
    }
</script>
</body>
</html>

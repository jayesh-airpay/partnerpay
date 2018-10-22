<div class="page-header">
    <h4>SpiceJet Agent Registration</h4>
</div>

<div class="fliterbox">
    <form id="agent_registration_filter">
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label>Agent ID</label>
                <select id="agent" name="agent" class="form-control">
                    <option value="">All</option>
                    <?php
                    if(!empty($agents_data)) {
                        foreach ($agents_data as $agent) {
                    ?>
                            <option value="<?php echo $agent['AGENT_DETAILS_ID'];?>"><?php echo $agent['AGENT_ID'];?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Group ID</label>
                <select id="group" name="group" class="form-control">
                    <option value="">All</option>
                    <?php
                    if(!empty($groups_data)) {
                        foreach ($groups_data as $group) {
                    ?>
                            <option value="<?php echo $group['SPIICEJET_GROUP_ID'];?>"><?php echo $group['GROUP_NAME'];?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Registration Date</label>
                <input type="text" name="registration_date" value="" class="form-control datepicker" id="registration_date"/>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label>Agency Name</label>
                <select id="agency" name="agency" class="form-control">
                    <option value="">All</option>
                    <?php
                    if(!empty($agency_data)) {
                        foreach ($agency_data as $agency) {
                            ?>
                            <option value="<?php echo $agency['AGENT_DETAILS_ID'];?>"><?php echo $agency['COMPANY_NAME'];?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group gr-height">
                <input type="button" class="btn btn-primary" id="search" value="Search">
            </div>
        </div>
    </form>
    </div>
</div>


<div class="tablebox">
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th class="text-center idnum">#</th>
                <th class="text-center">Date Of Registration</th>
                <th class="text-center">Agent ID</th>
                <th class="text-center">Payment Instrument</th>
                <th class="text-center">Card Status</th>
                <th class="text-center">Email ID</th>
                <th class="text-center">Group ID</th>
            </tr>
            </thead>

            <tbody>
            <?php
            if(!empty($agent_registration_data)) {
                $i = 0;
                foreach ($agent_registration_data as $data) {
                    $i++;
            ?>
                <tr>
                    <td class="idnum"><?php echo $i;?></td>
                    <td><?php echo date('d-m-Y',$data['CREATED_ON']);?></td>
                    <td><?php echo $data['AGENT_ID'];?></td>
                    <?php
                    if(empty($data['BANK_ID'])) {
                    ?>
                        <td><?php echo $data['CARD_NUMBER'];?></td>
                    <?php
                    } else {
                        $connection = \Yii::$app->db;
                        $bank_id = $data['BANK_ID'];
                        $get_agent_banks = $connection->createCommand('SELECT A.* FROM tbl_bank as A WHERE A.BANK_ID =:bank_id');
                        $get_agent_banks->bindValue(':bank_id', $bank_id);
                        $agent_banks = $get_agent_banks->queryOne();
                        if (!empty($agent_banks)) {
                    ?>
                            <td><?php echo $agent_banks['BANK_NAME']; ?></td>
                    <?php
                        }
                    }
                    ?>
                    <td><?php echo ($data['STATUS'] == 1)?'Active':'Deactive';?></td>
                    <td><?php echo $data['EMAIL'];?></td>
                    <td><?php echo $data['AGENT_GROUP_ID'];?></td>
                </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<nav class="pull-right">
    <ul class="pager">
        <li><a href="#"><span class="glyphicon glyphicon-chevron-left"></a></li>
        <li><a href="#"><span class="glyphicon glyphicon-chevron-right"></a></li>
    </ul>
</nav>
<link rel="stylesheet" href="../resources/css/jquery-ui.css" type="text/css" />
<script type="text/javascript" src="../resources/js/jquery.js"></script>
<script type="text/javascript" src="../resources/js/jquery-ui.js"></script>
<script>
    $( ".datepicker" ).datepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true
    });
    $("#search").click(function () {
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
                /*var msg = '';
                resp_object = JSON.parse(resp);
                console.log(resp_object);
                if(resp_object.Result == 'Success') {
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
                        location.reload();
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
                return false;*/
            });
        } else {
            return fase;
        }
    });
</script>

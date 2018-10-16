<link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/bbps/css/customs.css" type="text/css">
<!-- <link rel="stylesheet" href="/partnerpay/modules/resources/css/dataTables.bootstrap.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css"> -->
<?php Yii::$app->getSession()->setFlash('page','listing')?>
<div class="container">
	<div class="loader"></div>
	<div class="page-header">
		<h4>Invoice Listing</h4>
		<div class="fieldstx">
				<a class="btn btn-default" href="/bbps/default/">Back</a>
		</div>
	</div>
				<div class="row">
        
		<div class="col-xs-10 col-sm-5 col-md-4 wallet-wrap">
			<div class="wallet">
				<div class="row">
					<div class="col-xs-8">
						Wallet balance: <span class="amount-tx" id="wallet_amount">Rs.<?php if($wallet_balance != ""){ echo $wallet_balance;} else {echo '0';}?></span>
					</div>
					<div class="col-sm-4">
						<!-- <a class="btn btn-success" href="javascript:walletTopUp()">Add Topup</a> -->
						<a href="#topup" class="btn btn-success" id="add_topup" data-toggle="modal" data-target="#topup" >Add Topup</a>
					</div>
				</div> 
				<div class="row">
					<div class="col-sm-12">
					<a class="viewHist" href="/bbps/default/view_wallet_history">View History</a>
					</div>
				</div>          
			</div>          
		</div>
		
		<div class="col-md-8">
			<div class="fliterbox">
			  <form id="remove_filter" action="javascript:loadData()">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>Select Biller</label>
							<select class="form-control" id="utility_select" onChange="getProviders()" name="utility">
							<option value="">SELECT UTILITY</option>
							<?php foreach($utility_data as $utility){?>
								<option value="<?=$utility['utility_id']?>"><?=$utility['utility_name']?></option>
							<?php  } ?>
							</select>		
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label>Select Categories </label>
							<select class="form-control" id="providers_select" name="providers">
								<option value="">Select Provider</option>
							</select>		
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<button type="submit" class="btn btn-primary fliterbtn" value="Submit">Submit</button>
					</div>
				</div>
			 </form>
			</div><br><!--close fliterbox -->
		</div>
	</div>
	<div id="tabs" class="hidden">		
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#dislist" id="unpaidlist" aria-controls="dislist" role="tab" data-toggle="tab">Unpaid bill list</a></li>
		<li role="presentation" ><a href="#unpaidinvoice" id="unpaidinvoicelist" aria-controls="dislist" onClick="unpaidInvoice()" role="tab" data-toggle="tab">Unpaid Invoice list</a></li>
		<li role="presentation"><a href="#alllist" id="allinvoice" aria-controls="alllist" role="tab" onClick="allInvoice()" data-toggle="tab">All Paid Invoice list</a></li>
		<li role="presentation"><a href="#registration_failed" id="registerationfailed" aria-controls="alllist" role="tab" onClick="registerationPending()" data-toggle="tab">Registeration Failed/Pending</a></li>
	</ul>
	<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="dislist">
				<div class="row">
							<div class="col-sm-6 col-md-4">
								<div class="form-group">
									<div class="form-group required">
										<label class="control-label req" for="">From Date</label>
										<input type="text" id="from_date" onChange="getRecords()" class="form-control datepicker1" name="frmdate" readonly>
										<div class="help-block"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-md-4">
								<div class="form-group">
									<div class="form-group required">
										<label class="control-label req" for="merchant-id">To Date</label>
										<input type="text" id="to_date" onChange="getRecords()" class="form-control datepicker2" name="todate" readonly>
										<div class="help-block"></div>
									</div>
								</div>
							</div>
				</div>
		
		
			<div class="removed-table hidden" id="removed">	
				<div class="tablebox non-tbbd">	
					<div class="table-responsive">
					<!-- <input type="button" class="btn btn-primary" value="Pay Selected" style=""> -->
			<table class="table table-striped table-bordered text-center" id="removed_data">
			<thead>
			<tr>
				<th class="text-center idnum">#</th>
				<th class="text-center idnum"><input type="checkbox" id="select_all" class="checkbox-inline"></th>
				<th class="text-center">Account Number</th>
				<th class="text-center">Due Date</th>
				<th class="text-center">Total Amount</th>
				<th class="text-center">Payment</th>
			</tr>
			<!-- <tr class="searchrow">
				<td class="idnum">&nbsp;</td>
				<td class="idnum">&nbsp;</td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td class="action">&nbsp;</td>
			</tr> -->
			</thead>
			
			<tbody>
			</tbody>

			
			</table>
			</div>
			</div>
			<!-- <nav class="pull-right">
			  <ul class="pager">
				<li><a href="#"><span class="glyphicon glyphicon-chevron-left"></a></li>
				<li><a href="#"><span class="glyphicon glyphicon-chevron-right"></a></li>
			  </ul>
			</nav>   -->
			</div>
		</div><!-- .tab-content close -->

		<div role="tabpanel" class="tab-pane" id="unpaidinvoice">
			<div class="tablebox non-tbbd">	
			<div class="table-responsive">
			<table id="unpaid_invoice" class="table table-striped table-bordered text-center">
			<thead>
			<tr>
				<th class="text-center idnum">#</th>
				<th class="text-center">Inovice Number</th>
				<th class="text-center">Provider Name</th>
                <th class="text-center">Utility Name</th>
				<th class="text-center">Total Amount</th>
				<th class="text-center action">&nbsp;</th>
			</tr>
			<!-- <tr class="searchrow">
				<td class="idnum">&nbsp;</td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td class="action">&nbsp;</td>
			</tr> -->
			</thead>
			
			<tbody>
			</tbody>

			
			</table>
			</div>
			</div>
			<!-- <nav class="pull-right">
			  <ul class="pager">
				<li><a href="#"><span class="glyphicon glyphicon-chevron-left"></a></li>
				<li><a href="#"><span class="glyphicon glyphicon-chevron-right"></a></li>
			  </ul>
			</nav> -->
		</div>
	
		<div role="tabpanel" class="tab-pane" id="alllist">
			<div class="tablebox non-tbbd">	
			<div class="table-responsive">
			<table id="all_invoice" class="table table-striped table-bordered text-center">
			<thead>
			<tr>
				<th class="text-center idnum">#</th>
				<th class="text-center">Provider Name</th>
                <th class="text-center">Utility Name</th>
				<!-- <th class="text-center">Issue Date</th>
				<th class="text-center">Due Date</th> -->
				<th class="text-center">Total Amount</th>
				<th class="text-center action">&nbsp;</th>
			</tr>
			<!-- <tr class="searchrow">
				<td class="idnum">&nbsp;</td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td class="action">&nbsp;</td>
			</tr> -->
			</thead>
			
			<tbody>
			</tbody>

			
			</table>
			</div>
			</div>
			<!-- <nav class="pull-right">
			  <ul class="pager">
				<li><a href="#"><span class="glyphicon glyphicon-chevron-left"></a></li>
				<li><a href="#"><span class="glyphicon glyphicon-chevron-right"></a></li>
			  </ul>
			</nav> -->
		</div><!-- .tab-content close -->
		<!-- .tab-content close -->	

	    <div role="tabpanel" class="tab-pane" id="registration_failed">
			<div class="tablebox non-tbbd">	
			<div class="table-responsive">
			<table id="failed_pending" class="table table-striped table-bordered text-center">
			<thead>
			<tr>
				<th class="text-center idnum">#</th>
				<th class="text-center">Account Number</th>
				<th class="text-center">Provider Name</th>
                <th class="text-center">Utility Name</th>
				<th class="text-center action">&nbsp;</th>
			</tr>
			<!-- <tr class="searchrow">
				<td class="idnum">&nbsp;</td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td><input type="text" class="form-control searchid" ></td>
				<td class="action">&nbsp;</td>
			</tr> -->
			</thead>
			
			<tbody>
			</tbody>

			
			</table>
			</div>
			</div>
			<!-- <nav class="pull-right">
			  <ul class="pager">
				<li><a href="#"><span class="glyphicon glyphicon-chevron-left"></a></li>
				<li><a href="#"><span class="glyphicon glyphicon-chevron-right"></a></li>
			  </ul>
			</nav> -->
		</div>

	</div>
	</div>
	<div class="modal fade" id="listing" tabindex="-1" role="dialog" aria-labelledby="listingLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Invoice listing</h4>
                </div>
                <div class="modal-body">
                    <div class="tableboxpopup">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center" id="paid_invoice">
                                <thead>
                                    <tr>
                                        <th class="text-center idnum">#</th>
                                        <th class="text-center">Account Number</th>
                                        <th class="text-center">Due Date</th>
                                        <th class="text-center">Total Amount</th>
                                        <th class="text-center action">Action</th>
                                    </tr>
                                    <!-- <tr class="searchrow">
                                        <td class="idnum">&nbsp;</td>
                                        <td><input type="text" class="form-control searchid"></td>
                                        <td><input type="text" class="form-control searchid"></td>
                                        <td><input type="text" class="form-control searchid"></td>
                                        <td><input type="text" class="form-control searchid"></td>
                                        <td><input type="text" class="form-control searchid"></td>
                                        <td class="action">&nbsp;</td>
                                    </tr> -->
                                </thead>

                                <tbody>
                                    
                                </tbody>
                            </table>
						</div>
                        <span style="color:#144913; font-size: 13px; ">* The failed transaction amount has been refunded to your account</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="topup" tabindex="-1" role="dialog" aria-labelledby="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Wallet Topup</h4>
      </div>
      <div class="modal-body">
		<div class="tableboxpopup">
        <div class="row">
        <form class="form" action="/bbps/default/pay" id="wallet_topup" method="post">
            <div class="col-xs-6">                            
                <div class="form-group">
                <input type="text" class="form-control" id="amount" name="invoice_amount" placeholder="Enter Topup Amount" value="">
        			<input type="hidden" name="payment_mode" id="payment_mode" value="va">
                    <input type="hidden" class="form-control" id="customvar" name="customvar" value="<?php echo "BBPS|".$_SERVER['REQUEST_URI'];?>">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group">                    
                <input type="submit" class="btn btn-primary lg-btn" value="Submit" name="submitButton">
                </div>
            </div>
        </form>
        </div>
		</div>
		
      </div>
    </div>
  </div>

<script type="text/javascript" src="/bbps/js/jquery.js"></script>  	
<script type="text/javascript" src="/bbps/js/bootstrap.file-input.js"></script>
<script type="text/javascript" src="/bbps/js/jquery-ui/jquery-ui.min.js"></script>

<script type="text/javascript">

		jQuery('.datepicker1').datepicker({"dateFormat":"yy-mm-dd"});
		jQuery('.datepicker2').datepicker({"dateFormat":"yy-mm-dd"});
	
	</script>
<script>
    $(document).ready(function(){
	 	$('#select_all').click(function(){
			 var dataTable = $('#removed_data').DataTable();
		dataTable.rows().nodes().to$().find('.checkbox').prop('checked',$(this).prop('checked'));
			//  $('input', $('#removed_data').fnGetNodes()).prop('checked',chk);
	 	});
	 	$('body').on('click', 'input.checkbox:checkbox', function() {
			if (!this.checked) {
            	$("#select_all").prop('checked', false);
         	}
		});
    });
</script>
<script>
function getProviders(){
	var id= $("#utility_select").val();
	if(id){
     	$.ajax({
      		url: "/bbps/default/providers",  
      		data: {utility_id: id},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
            	$('#providers_select').empty();
				if(data.length>0){
           		$.each(data, function (key, value) {
                    var provider_list='<option value="'+value.id+'">'+value.name+'</option>';
                    $('#providers_select').append(provider_list);
             	});
				} else {
					var provider_list='<option value="">Select Provider</option>';
                    $('#providers_select').append(provider_list);
				}
      		}
   		})
	}
}
</script>
<script>
function loadData(){
	$('#tabs').removeClass('hidden');
	var active_tab = $('.tab-pane.active').attr('id');
	console.log($('.tab-pane.active').attr('id'));
	if(active_tab == 'dislist'){
		getRecords();
	} else if(active_tab == 'alllist'){
		allInvoice();
	} else if (active_tab == 'unpaidinvoice'){
		unpaidInvoice();
	} else {
		registerationPending();
	}
}

function getRecords(){
	var utility= $("#utility_select").val();
	var provider= $("#providers_select").val();
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	if(utility && provider){
		$.ajax({
      		url: "/bbps/default/unpaid",  
      		data: {"utility_id": utility,"provider_id": provider,'from_date':from_date,'to_date':to_date,"_csrf":csrf_token},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  $('#removed').removeClass('hidden');
				  if($.fn.DataTable.isDataTable( '#removed_data' )){
				  	$("#removed_data").DataTable().destroy();
				  }
				  $('#removed_data tbody').empty();
				$.each(data, function (key, value) {
                    var remove_data='<tr><td class="idnum">'+(parseInt(key)+1)+'</td>'+
						'<td><input type="checkbox" name="checkbox_bill[]" class="checkbox checkbox-inline" value="'+value.PROVIDER_BILL_DETAILS_ID+'" class="checkbox-inline"></td>'+
						'<td>'+value.ACCOUNT_NO+'</td>'+
						'<td>'+value.DUE_DATE+'</td>'+
						'<td>'+value.AMOUNT+'</td>'+
						'<td><a href="javascript:void(0)" onClick="pay()" class="btn btn-primary">Pay Now</a></td></tr>';
                    $('#removed_data tbody').append(remove_data);
             	});
				 $("#removed_data").DataTable({
    				    "paging": true,
        				"searching": true,
        				'autowidth': true,
        				"ordering": false,
        				"lengthMenu": [10, 25, 50, 75, 100],
						"buttons": [
        						'selectAll',
        						'selectNone'
								],
					 });
					 $('div.dataTables_filter input').addClass('searchable')
			}
		  });
		}	
}
</script>
<script>
function pay(){
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	var arr = [];
	var dataTable = $('#removed_data').DataTable();
	dataTable.rows().nodes().to$().find('input.checkbox:checkbox:checked').each(function () {
            arr.push($(this).val());
		});
		console.log(arr);
		if(arr.length>0){
        	$('.loader').show();
			$.ajax({
      			url: "/bbps/default/add_mobile",  
      			data: {"provider_bill_details_id":arr,"_csrf":csrf_token},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
					window.location.href = '/bbps/default/payment?invoice_id='+data;
				}
   			});
		} else {
			alert("Please select a Mobile Number");
		}
}

function getDetails(invoice_id){
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	$('#paid_invoice tbody').empty();
	$.ajax({
      			url: "/bbps/default/get_invoice_data",  
      			data: {"invoice_id":invoice_id,"_csrf":csrf_token},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
					  $('#paid_invoice tbody').empty();
					  $.each(data, function (key, value) {
						var paid_invoice_data = '<tr><td class="idnum">'+(parseInt(key)+1)+'</td><td>'+value.ACCOUNT_NO+'</td><td>'+value.DUE_DATE+'</td><td>'+value.AMOUNT+'</td>';
						if(value.PAYMENT_STATUS == "success"){
							paid_invoice_data = paid_invoice_data+'<td class="action"><div class="bbox"><a href="/bbps/default/generate_bill_receipt?bill_details_id='+value.PROVIDER_BILL_DETAILS_ID+'" target="_blank" class="btn btn-success"><span>RECEIPT</span></a></div></td></tr>';
						} else if(value.PAYMENT_STATUS == 'fail') {
							paid_invoice_data = paid_invoice_data+'<td class="action"><div class="bbox textbg"><span class="failed">FAILED</span></div></td></tr>';
						} else {
							paid_invoice_data = paid_invoice_data+'<td class="action"><div class="bbox textbg"><span class="inprocess">IN PROCESS</span></div></td></tr>';
						}
						$('#paid_invoice tbody').append(paid_invoice_data);
					  });
				  }
   				});
}

function allInvoice(){
	var utility= $("#utility_select").val();
	var provider= $("#providers_select").val();
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	if(utility && provider){
		$.ajax({
      		url: "/bbps/default/paid_invoice",  
      		data: {"utility_id": utility,"provider_id": provider,"_csrf":csrf_token},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  $('#all_invoice').removeClass('hidden');
				  if($.fn.DataTable.isDataTable( '#all_invoice' )){
				  	$("#all_invoice").DataTable().destroy();
				  }
				  $('#all_invoice tbody').empty();
				$.each(data, function (key, value) {
                    var all_invoice_data='<tr><td class="idnum">'+(parseInt(key)+1)+'</td><td>'+value.provider_name+'</td><td>'+value.utility_name+'</td><td id="amount_'+value.INVOICE_ID+'">'+value.invoice_amount+'</td><td class="action"><div class="bbox"><a href="#listing" style="margin-left:25px" data-toggle="modal" onClick="getDetails('+value.INVOICE_ID+')" class="btn btn-primary" >DETAILS</a></div></td></tr>';
                    $('#all_invoice tbody').append(all_invoice_data);
             	});
				 $("#all_invoice").DataTable({
    				    "paging": true,
        				"searching": true,
        				'autowidth': true,
        				"ordering": false,
        				"lengthMenu": [10, 25, 50, 75, 100],
						"buttons": [
        						'selectAll',
        						'selectNone'
    							],
					 });
					 $('div.dataTables_filter input').addClass('searchable')
			}
		  });
		}
}

function unpaidInvoice(){
	var utility= $("#utility_select").val();
	var provider= $("#providers_select").val();
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	if(utility && provider){
		$.ajax({
      		url: "/bbps/default/unpaid_invoice",  
      		data: {"utility_id": utility,"provider_id": provider,"_csrf":csrf_token},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  $('#unpaid_invoice').removeClass('hidden');
				  if($.fn.DataTable.isDataTable( '#unpaid_invoice' )){
				  	$("#unpaid_invoice").DataTable().destroy();
				  }
				  $('#unpaid_invoice tbody').empty();
				$.each(data, function (key, value) {
                    var all_invoice_data='<tr><td class="idnum">'+(parseInt(key)+1)+'</td><td>'+value.INVOICE_ID+'</td><td>'+value.provider_name+'</td><td>'+value.utility_name+'</td><td id="amount_'+value.INVOICE_ID+'">'+value.invoice_amount+'</td><td><a href="/bbps/default/payment?invoice_id='+value.INVOICE_ID+'" onClick="loader()" class="btn btn-primary">Pay Now</a></td></tr></tr>';
                    $('#unpaid_invoice tbody').append(all_invoice_data);
             	});
				 $("#unpaid_invoice").DataTable({
    				    "paging": true,
        				"searching": true,
        				'autowidth': true,
        				"ordering": false,
        				"lengthMenu": [10, 25, 50, 75, 100],
						"buttons": [
        						'selectAll',
        						'selectNone'
    							],
					 });
					 $('div.dataTables_filter input').addClass('searchable')
			}
		  });
		}
}

function loader(){
	$('.loader').show();
}

function registerationPending(){
	var utility= $("#utility_select").val();
	var provider= $("#providers_select").val();
	var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
	if(utility && provider){
		$.ajax({
      		url: "/bbps/default/registeration_pending_failed",  
      		data: {"utility_id": utility,"provider_id": provider,"_csrf":csrf_token},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  $('#failed_pending').removeClass('hidden');
				  if($.fn.DataTable.isDataTable( '#failed_pending' )){
				  	$("#failed_pending").DataTable().destroy();
				  }
				  $('#failed_pending tbody').empty();
				$.each(data, function (key, value) {
                    var failed_registeration='<tr><td class="idnum">'+(parseInt(key)+1)+'</td><td>'+value.ACCOUNT_NO+'</td><td>'+value.provider_name+'</td><td>'+value.utility_name+'</td>';
					if(value.PAYMENT_STATUS == 'fail') {
						failed_registeration = failed_registeration+'<td class="action"><div class="bbox textbg"><span class="failed">FAILED</span></div></td></tr>';
						} else {
							failed_registeration = failed_registeration+'<td class="action"><div class="bbox textbg"><span class="inprocess">IN PROCESS</span></div></td></tr>';
						}
                    $('#failed_pending tbody').append(failed_registeration);
             	});
				 $("#failed_pending").DataTable({
    				    "paging": true,
        				"searching": true,
        				'autowidth': true,
        				"ordering": false,
        				"lengthMenu": [10, 25, 50, 75, 100],
						"buttons": [
        						'selectAll',
        						'selectNone'
    							],
					 });
					 $('div.dataTables_filter input').addClass('searchable')
			}
		  });
		}
}

$(document).ready(function(){
	$('#utility_select').val('');
	$('#providers_select').val('');
});
</script>
</div>
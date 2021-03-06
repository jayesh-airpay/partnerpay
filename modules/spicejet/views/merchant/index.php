
    <link rel="shortcut icon" type="image/x-icon" href="/partnerpay/web/spicejet/resources/images/favicon.ico">
        <link rel="stylesheet" href="/partnerpay/web/spicejet/resources/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="/partnerpay/web/spicejet/resources/css/font-awesome.css" type="text/css" />
        <link rel="stylesheet" href="/partnerpay/web/spicejet/resources/css/custom.css" type="text/css" />

<div class="wrapper">
<div class="container">
        		<div class="alert alert-success alert-dismissable notification hidden">
        		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        		</div>
	<div class="approvalpage">
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#userlist" aria-controls="userlist" role="tab" data-toggle="tab">User Listing</a></li>
			<li><a href="#cardapp" aria-controls="cardapp"  onClick="get_cards()" role="tab" data-toggle="tab">Card Approval</a></li>
			<li><a href="#group" aria-controls="group" onClick="get_group()" role="tab" data-toggle="tab">Create Group</a></li>
			<li><a href="#accmange" aria-controls="accmange" onClick="account_mangement()" role="tab" data-toggle="tab">Account Management</a></li>
			<li><a href="#transOpe" aria-controls="transOpe" role="tab" data-toggle="tab">Transaction Operations</a></li>
			<li><a href="#limit" aria-controls="limit" role="tab" onClick="set_limit()" data-toggle="tab">Manage Limit</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="userlist">
					<div class="page-header">
						<h4>User Listing</h4>
						<!-- <div class="fieldstx">
								<a class="btn btn-default" href="./index.html">Back</a>
						</div> -->
						<!-- <div class="summary">Displaying <b>1-4</b> of <b>4</b> items.</div> -->
					</div>
					
					
					<div class="tablebox">	
							<div class="table-responsive">
							<table class="table table-striped table-bordered text-center" id="agent_list">
							<thead>
							<tr>
								<th class="text-center idnum">#</th>
								<th class="text-center">Company Name</th>
								<th class="text-center">First Name</th>
								<th class="text-center">Last Name</th>
								<th class="text-center">Mobile Number</th>
								<th class="text-center">Email</th>
								<th class="text-center">&nbsp;</th>
							</tr>
							<tr class="searchrow">
								<td class="idnum">&nbsp;</td>
								<td><input type="text" class="form-control agent_list_search" id="company_search"></td>
								<td><input type="text" class="form-control agent_list_search" id="fname_search"></td>
								<td><input type="text" class="form-control agent_list_search" id="lname_search"></td>
								<td><input type="text" class="form-control agent_list_search" id="phone_search"></td>
								<td><input type="text" class="form-control agent_list_search" id="email_search"></td>
								<td class="action">&nbsp;</td>
							</tr>
							</thead>
							
							<tbody>
								<?php 
								$i = 1;
								foreach($agent_details as $key=>$agent){
										$name = explode(' ',$agent['STAFF_NAME']);
									?>
									<tr>
										<td class="idnum"><?= $i;?></td>
										<td><?= $agent['COMPANY_NAME']; ?></td>
										<td><?= $name[0];?></td>
										<td><?= $name[1];?></td>
										<td><?= $agent['PHONE']; ?></td>
										<td><?= $agent['EMAIL']?></td>
										<td>
											<div class="btnAction">
												<a onClick="approve('<?=$agent['AGENT_DETAILS_ID'];?>')" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Approve</a>
												<a href="javascript:void(0)" onClick="reject('<?=$agent['AGENT_DETAILS_ID'];?>')" class="btn btn-danger">Reject</a>
											</div>
										</td>
									</tr>
								<?php } ?>
									</tbody>

							
							</table>
							</div>
						</div>
							<nav class="pagenav">
							<?php 
							if($agent_count[0]['total'] % 2 == 0){
                        			$total_pages = $agent_count[0]['total']/2;
                     			} else {
                        			$total_pages = floor($agent_count[0]['total']/2)+1; 
                     		} ?>
					  <ul class="pager pull-right">
						<li><a href="javascript:void(0)" id="1"  onClick="previous()" class="previous"><span class="glyphicon glyphicon-chevron-left"></a></li>
						<li><a href="javascript:void(0)" id="1" data-max="<?= $total_pages;?>" onClick="next()" class="next"><span class="glyphicon glyphicon-chevron-right"></a></li>
					  </ul>
							</nav>
			</div>
			
			
			<div role="tabpanel" class="tab-pane" id="cardapp">
					<div class="page-header">
					<h4>Card Approval</h4>
					<!-- <div class="fieldstx">
							<a class="btn btn-default" href="./index.html">Back</a>
					</div> -->
					<!-- <div class="summary">Displaying <b>1-4</b> of <b>4</b> items.</div> -->
				</div>

				
				<div class="tablebox">	
						<div class="table-responsive">
						<table class="table table-striped table-bordered text-center" id="card">
						<thead>
						<tr>
							<th class="text-center idnum">#</th>
							<th class="text-center">Agent Id</th>
							<th class="text-center">Email</th>
							<th class="text-center">Payment Instrument</th>
							<th class="text-center action">Action</th>
						</tr>
						<tr class="searchrow">
							<td class="idnum">&nbsp;</td>
							<td><input type="text" class="form-control searchid card_list_search" id="agent_id_search" ></td>
							<td><input type="text" class="form-control searchid card_list_search" id="card_email_search"></td>
							<td><input type="text" class="form-control searchid card_list_search" id="payment_instrument_search"></td>
							<td class="action">&nbsp;</td>
						</tr>
						</thead>
						
						<tbody>
								<!-- <tr>
									<td class="idnum">1</td>
									<td>bom352905</td>
									<td>rohit.verma@gmail.com</td>
									<td>4147 67XX XXXX 9608</td>
									<td class="action">
										<div class="bbox bbox4">
											<a href="#" title="Approve" data-toggle="modal" data-target="#approve"><span class="glyphicon glyphicon-ok"></span></a>
											<a href="#" title="Reject"><span class="glyphicon glyphicon-remove"></span></a> -->
											<!-- <a href="#" title="Update agent details"><span class="glyphicon glyphicon-pencil"></span></a>
											<a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a> -->
										<!-- </div>
									</td>
								</tr> -->
								</tbody>

						
						</table>
						</div>
					</div>
						<nav class="pagenav">
						<ul class="pager pull-right">
							<li><a href="javascript:void(0)" id="1"  onClick="previous_card()" class="previous_card"><span class="glyphicon glyphicon-chevron-left"></a></li>
							<li><a href="javascript:void(0)" id="1" data-max="" onClick="next_card()" class="next_card"><span class="glyphicon glyphicon-chevron-right"></a></li>
					 	</ul>
						</nav>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="accmange">
					<div class="page-header">
						<h4>Account Management</h4>
						<a data-toggle="modal" data-target="#addmerchant" title="Edit"><input type="button" id="add_new" name="add_new" value="ADD NEW" style="float:right"></a>
					</div>
					<div class="tablebox">	
						<div class="table-responsive">
						<table class="table table-striped table-bordered text-center" id="account">
						<thead>
						<tr>
							<th class="text-center idnum">#</th>
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Role</th>
							<th class="text-center">Created</th>
							<th class="text-center action">Action</th>
						</tr>
						<!-- <tr class="searchrow">
							<td class="idnum">&nbsp;</td>
							<td><input type="text" class="form-control searchid" ></td>
							<td><input type="text" class="form-control searchid" ></td>
							<td><input type="text" class="form-control searchid" ></td>
							<td class="action">&nbsp;</td>
						</tr> -->
						</thead>
						
						<tbody>
								<!-- <tr>
									<td class="idnum">1</td>
									<td>Rohit Verma</td>
									<td>rohit.verma@gmail.com</td>
									<td>Merchant Admin</td>
									<td>20 Sep 2018</td>
									<td class="action">
										<div class="bbox">
											<a href="#" data-toggle="modal" data-target="#editmerchant" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
											<a href="#" title="Delect"><span class="glyphicon glyphicon-trash"></span></a>
										</div>
									</td>
								</tr> -->
								</tbody>

						
						</table>
						</div>
					</div>
						<nav class="pagenav">
						<ul class="pager pull-right">
							<li><a href="javascript:void(0)" id="1"  onClick="previous_account()" class="previous_account"><span class="glyphicon glyphicon-chevron-left"></a></li>
							<li><a href="javascript:void(0)" id="1" data-max="" onClick="next_account()" class="next_account"><span class="glyphicon glyphicon-chevron-right"></a></li>
					 	</ul>
						</nav>				
			</div>
			
			<div role="tabpanel" class="tab-pane" id="group">
					<div class="page-header">
						<h4>Create Group</h4>
					</div>
					
					<div class="">
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="group_name_div">
									<input type="text" class="form-control" id="group_name" name="group_name" placeholder="Group Name">
									<div class="help-block" id='group_name_error'></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="group_status_div">
									<label>Status</label>
									<select class="form-control" id="group_status" name="group_status">
										<option value="">Select Status</option>
										<option value="1">Activate</option>
										<option value="0">Deactivate</option>
									</select>
									<div class="help-block" id='group_status_error'></div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group">
									<input type="button" class="btn btn-primary lg-btn" id="groupbtn" value="Submit">
								</div>
							</div>
						</div>
					</div>
							
					
					<hr>
					<h5>Group details</h5>		
						<div class="tablebox no-mar">	
							<div class="table-responsive">
							<table class="table table-striped table-bordered text-center" id="group">
							<thead>
							<tr>
								<th class="text-center">Group ID</th>
								<th class="text-center">Group Name</th>
								<th class="text-center">Status</th>
								<th class="text-center">Created Date</th>
								<th class="text-center action">Action</th>
							</tr>
							</thead>
							
							<tbody>
							</tbody>

							
							</table>
							</div>
						</div>
						<nav class="pagenav">
							<ul class="pager pull-right">
							  	<li><a href="javascript:void(0)" id="1" onClick="previous_group()" class="previous_group"><span class="glyphicon glyphicon-chevron-left"></a></li>
								<li><a href="javascript:void(0)" id="1" data-max="" onClick="next_group()" class="next_group"><span class="glyphicon glyphicon-chevron-right"></a></li>
						  	</ul>
						</nav>	
			</div>
			
			<div role="tabpanel" class="tab-pane" id="transOpe">
					<div class="page-header">
						<h4>Transaction Query System</h4>
					</div>
					
					
					<div class="fliterbox">
						<div class="row">
							<div class="col-sm-9 flside-left">
					
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Merchant ID">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Enter Start Date">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Enter Last Date">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Airpay Transaction ID">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Types - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Pgs - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Merchant Transaction ID">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Status - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Payments Modes - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Source Transaction ID">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Card Number">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Card Schemes - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="PG Transaction ID">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="row">
										<div class="col-xs-6">									
											<div class="form-group">
												<input type="text" class="form-control" placeholder="Amount">
											</div>
										</div>
										<div class="col-xs-6">
											<div class="form-group">
												<select class="form-control">
													<option value="">equals</option>
													<option value="">International </option>
													<option value="">Domestic</option>
												</select>
											</div>
										</div>								
									</div>						
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Banks - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Issuer Reference No.">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Custom Parameter">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Transaction - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Auth ID Code">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Phone Number">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Email Id">
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<select class="form-control">
											<option value="">Currency - All</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
								</div>
							</div>

							
							</div>
							<div class="col-sm-3">
								<div class="row">
									<div class="col-sm-12">
									<div class="form-group">
										<select class="form-control">
											<option value="">Favorites</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
									</div>
									<div class="col-sm-12">
									<div class="form-group">
										<select class="form-control">
											<option value="">Recent</option>
											<option value="">International </option>
											<option value="">Domestic</option>
										</select>
									</div>
									</div>
								</div>
							</div>
						
						</div>						
					</div>
					
					<div class="tablebox no-mar">	
						<div class="table-responsive">
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
								<tr>
									<td class="idnum">1</td>
									<td>20-09-2018 <br>01.00 pm</td>
									<td>bom352905</td>
									<td>Sale </td>
									<td>Success </td>
									<td>INR 5000.00</td>
									<td>ICICI Bank PG</td>
								</tr>
								<tr>
									<td class="idnum">2</td>
									<td>22-09-2018 <br>09.00 am</td>
									<td>bom52905</td>
									<td>Sale</td>
									<td>Success </td>
									<td>INR 3500.00</td>
									<td>ICICI Bank PG</td>
								</tr>
								<tr>
									<td class="idnum">3</td>
									<td>25-09-2018 <br>04.28 pm</td>
									<td>bom59302</td>
									<td>Sale</td>
									<td>Success </td>
									<td>INR 2000.00</td>
									<td>ICICI Bank PG</td>
								</tr>
							</tbody>

			
						</table>
					</div>
					
					</div>
					<nav class="pagenav">
					<?php if($agent_count[0]['AGENT_DETAILS_ID'] % 2 == 0){
                        			$total_pages = $total_count['total']/2;
                     			} else {
                        			$total_pages = floor($total_count['total']/2)+1; 
                     		} ?>
					  <ul class="pager pull-right">
						<li><a href="javascript:void(0)" id="0" class="previous" disabled><span class="glyphicon glyphicon-chevron-left"></a></li>
						<li><a href="javascript:void(0)" id="<?= $total_pages;?>" class="next"><span class="glyphicon glyphicon-chevron-right"></a></li>
					  </ul>
					</nav>	
					
			</div>
			
			<div role="tabpanel" class="tab-pane" id="limit">
					<div class="page-header">
						<h4>Manage Limit</h4>
					</div>
					
					
						<div class="row">
						<div class="col-sm-6 col-md-5 col-lg-4">
							<div class="form-group">
								<select class="form-control" id="groupid" name="groupid">
								</select>
							</div>
						</div>
					</div>
					
					<div class="limitbox" style="display:none;">
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="overall_group_limit_div">
									<input type="text" class="form-control" placeholder="Group Transaction Limit (Per Group)" id="overall_group_limit" name="overall_group_limit">
									<div class="help-block" id='overall_group_limit_error'></div>
								</div>
							</div>
						</div>
						<h4>Minimum Upload Limit Per Agent:</h4>
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="credit_card_lower_Limit_div">
									<!-- <label>Below Group Transaction Limit</label> -->
									<input type="text" class="form-control" placeholder="Below Group Transaction Limit" id="credit_card_lower_Limit" name="credit_card_lower_Limit" value="">
									<div class="help-block" id='credit_card_lower_Limit_error'></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="credit_card_upper_limit_div">
									<!-- <label>Above Group Transaction Limit</label> -->
									<input type="text" class="form-control" placeholder="Above Group Transaction Limit" id="credit_card_upper_limit" name="credit_card_upper_limit" value="">
									<div class="help-block" id='credit_card_upper_limit_error'></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="debit_card_limit_div">
									<!-- <label>Debit Card Global Transaction Limit</label> -->
									<input type="text" class="form-control" placeholder="Debit Card Global Transaction Limit" id="debit_card_limit" name="debit_card_limit" value="">
									<div class="help-block" id='debit_card_limit_error'></div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group" id="netbanking_limit_div">
									<!-- <label>Net Banking Global Transaction Limit</label> -->
									<input type="text" class="form-control" placeholder="Net Banking Global Transaction Limit" id="netbanking_limit" name="netbanking_limit" value="">
									<div class="help-block" id='netbanking_limit_error'></div>
								</div>
							</div>
						</div>
							
							<div class="row">
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group">
										<input type="button" class="btn btn-primary lg-btn" id="limitbtn" value="Submit">
									</div>
								</div>
							</div>
					</div>
			</div>
			
		</div>

	</div>
	

		
	
	
	
</div>
</div>



<div class="footer">	
	<div class="container copyright"><p>Copyright © 2018 by airpay.</p></div>
</div>




<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Access details (ONLY FOR SPICEJET USE)</h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="agent_id_div">
				<input type="text" class="form-control" placeholder="Agency ID Number" id="agent_id" name="agent_id" value = "">
				<div class="help-block" id='agent_id_error'></div>
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="agent_password_div">
				<input type="text" class="form-control" placeholder="Agency Password" id="agent_password" name="agent_password" value="">
				<input type="hidden" name="agent_details_id" id="agent_details_id" value="">
				<div class="help-block" id = 'agent_password_error'></div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">					
				<input type ="button" class="btn btn-primary lg-btn" id="approve_agent" value="Submit">
				</div>
			</div>
		</div>
      </div>
   
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="approve" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agent details </h4>
      </div>
	  <div class="modal-body hidden" id="ungrouped">
							 <span style="colour:red"> PLEASE ADD AGENT TO A GROUP </span>
	  </div>
      <div class="modal-body hidden" id="grouped">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					Agent ID:<span id="card_agent"> </span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="group_id_div">
				<input type="text" class="form-control" placeholder="Group ID" id="group_id" name="group_id" disabled>
				<div class="help-block" id = 'group_id_error'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="mobile_no_div">
				<input type="text" class="form-control" placeholder="Mobile" id="mobile_no" name="mobile_no">
				<input type="hidden" value="" name="agent_payment_config_id" id="agent_payment_config_id">
				<div class="help-block" id = 'mobile_no_error'></div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">					
				<input type="submit" class="btn btn-primary lg-btn" id="update_card_status" value="Update">
				</div>
			</div>
		</div>
      </div>
   
    </div>
  </div>
</div>






<!-- Modal -->
<div class="modal fade" id="editmerchant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Merchant User </h4>
      </div>
      <div class="modal-body">		
			<div class="row">
				<div class="col-sm-6 col-md-5">
					<div class="form-group" id="fname_div">
						<input type="text" class="form-control" placeholder="First Name" id="fname" name="fname" value="">
						<div class="help-block" id = 'fname_error'></div>
					</div>
				</div>
				<div class="col-sm-6 col-md-5">
					<div class="form-group req" id="lname_div">
						<input type="text" class="form-control" placeholder="Last Name" id="lname" name="lname" value="">
						<div class="help-block" id = 'lname_error'></div>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-6 col-md-5">
					<div class="form-group" id="mail_div">
						<input type="text" class="form-control" placeholder="Email" id="mail" name="mail" value="">
						<div class="help-block" id = 'mail_error'></div>
					</div>
				</div>
				<div class="col-sm-6 col-md-5">
					<div class="form-group req" id="mob_div">
						<input type="text" class="form-control" placeholder="Mobile" id="mob" name="mob" value="">
						<input type="hidden" name="userid" id="userid" value="">
						<div class="help-block" id = 'mob_error'></div>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-sm-12">
					<label>Permissions</label>
					<div class="checkbox checkbox-list">
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox1" value="option1">Merchant Admin
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox2" value="option2"> Merchant Finance/Ops
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option3"> Merchant Designer
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option4"> Merchant Sales
						</label>

					</div>
				</div>
			</div> -->
		
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">					
				<input type="submit" class="btn btn-primary lg-btn" id="update_account" value="Update">
				</div>
			</div>
		</div>
      </div>
   
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="mapagency" tabindex="-1" role="dialog" aria-labelledby="mapagency">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Map Agency Details </h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-sm-6">				
				<div class="form-group req" id="agency_id_div">
					<input type="text" class="form-control" name="agency_id" id="agency_id" placeholder="Agency ID">
					<input type="hidden" name="agent_details_id" id="agent_details_id" value="">
					<div class="help-block" id = 'agency_id_error'></div>
				</div>			
			</div>
			
			<div class="col-sm-6">
				<div class="form-group req" id="agency_name_div">
				<input type="text" class="form-control" placeholder="Agency Name" id="agency_name" name="agency_name" disabled>
				<div class="help-block" id = 'agency_name_error'></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="city_div">
				<input type="text" class="form-control" placeholder="City" id="city" name="city" disabled>
				<div class="help-block" id = 'city_error'></div>
				</div>
			</div>
			
			<div class="col-sm-6">
				<div class="form-group req" id="state_div">
				<input type="text" class="form-control" placeholder="State" id="state" name="state" disabled>
				<div class="help-block" id = 'state_error'></div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group req" id="pan_card_div">
				<input type="text" class="form-control" placeholder="Pan Card" id="pan_card" name="pan_card" disabled>
				<div class="help-block" id = 'pan_card_error'></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group req">
				<input type="text" class="form-control" placeholder="Group Name" value="" id="group_name_map" name="group_name_map">
				<input type="hidden" value="" name="group_id_map" id="group_id_map">
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group" id="agent_status_div">
					<label>Status</label>
					<select class="form-control" id="agent_status" name="agent_status">
						<option value="">Select Status</option>
						<option value="1">Activate</option>
						<option value="0">Deactivate</option>
					</select>
					<div class="help-block" id = 'agent_status_error'></div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">					
				<input type="button" class="btn btn-primary lg-btn" id="map_agency" value="Map Agency">
				</div>
			</div>
		</div>
      </div>
   
    </div>
  </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="addmerchant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Merchant User </h4>
      </div>
      <div class="modal-body">		
			<div class="row">
				<div class="col-sm-6 col-md-5">
					<div class="form-group" id="add_fname_div">
						<input type="text" class="form-control" placeholder="First Name" id="add_fname" name="add_fname" value="">
						<div class="help-block" id = 'add_fname_error'></div>
					</div>
				</div>
				<div class="col-sm-6 col-md-5">
					<div class="form-group req" id="add_lname_div">
						<input type="text" class="form-control" placeholder="Last Name" id="add_lname" name="add_lname" value="">
						<div class="help-block" id = 'add_lname_error'></div>
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-6 col-md-5">
					<div class="form-group" id="add_mail_div">
						<input type="text" class="form-control" placeholder="Email" id="add_mail" name="add_mail" value="">
						<div class="help-block" id = 'add_mail_error'></div>
					</div>
				</div>
				<div class="col-sm-6 col-md-5">
					<div class="form-group req" id="add_mob_div">
						<input type="text" class="form-control" placeholder="Mobile" id="add_mob" name="add_mob" value="">
						<div class="help-block" id = 'add_mob_error'></div>
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-sm-12">
					<label>Permissions</label>
					<div class="checkbox checkbox-list">
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox1" value="option1">Merchant Admin
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox2" value="option2"> Merchant Finance/Ops
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option3"> Merchant Designer
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option4"> Merchant Sales
						</label>

					</div>
				</div>
			</div> -->
		
		<div class="row">
			<div class="col-sm-6 col-md-5 col-lg-4">
				<div class="form-group">					
				<input type="submit" class="btn btn-primary lg-btn" id="add_account" value="ADD">
				</div>
			</div>
		</div>
      </div>
   
    </div>
  </div>
</div>

<!-- END MODAL -->

<!-- jQuery -->
<script type="text/javascript" src="../resources/js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script type="text/javascript" src="../resources/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../resources/js/bootstrap.file-input.js"></script>
<script type="text/javascript" src="../resources/js/custom.js"></script>

<script type="text/javascript">
	$("#approve_agent").click(function () {
		validate = validation();
		if(validate){
			var agent_id = $('#agent_id').val();
			var agent_password = $('#agent_password').val();
			var id = $('#agent_details_id').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/approve",  
      			data: {agent_details_id: id,agents_id:agent_id,password:agent_password},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
            		getdata();
      			}
   		})
		}
	});	

	$("#groupbtn").click(function(){
		var validate = group_validation();
		if(validate){
			var group_name = $('#group_name').val();
			var group_status = $('#group_status').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/addgroup",  
      			data: {name:group_name,status:group_status},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
					$('#group_name').val("");
					$('#group_status').val("");
					$('#group_name_div').removeClass('has-error');
					$('#group_name_error').empty();
					$('#group_status_div').removeClass('has-error');
					$('#group_status_error').empty();
					  if(data == '1'){
						get_group();
						$('.notification').html('Group added successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while adding new group');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
      			}
   		})
		}
	});

	$('#agency_id').change(function(){
		if($('#agency_id').val()!=""){
			var agent_id=$('#agency_id').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/getagentdetail",  
      			data: {id:agent_id},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
					if(data['status']!=0){
						$('#agency_name').val(data['COMPANY_NAME']);
						$('#city').val(data['CITY']);
						$('#state').val(data['STATE']);
						$('#pan_card').val(data['PAN_NO']);
						$('#agent_details_id').val(data['AGENT_DETAILS_ID']);
						$('#agency_id_div').removeClass('has-error');
						$('#agency_id_error').empty();
					}else{
						$('#agency_id_div').addClass('has-error');
						$('#agency_id_error').empty().html('Incorrect Agent Id');
						$('#agency_name').val("");
						$('#city').val("");
						$('#state').val("");
						$('#pan_card').val("");
						$('#agent_details_id').val("");
					}
      			}
		});
	 }
	});

	$("#map_agency").click(function () {
		validate = agent_map_validation();
		if(validate){
			var agent_details_id = $('#agent_details_id').val();
			var group_id = $('#group_id_map').val();
			var status = $('#agent_status').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/mapagent",  
      			data: {id:agent_details_id,groupid:group_id,agent_group_status:status},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
						$('#mapagency').modal('hide');
						$('.modal-backdrop').remove();
					  if(data =='1'){
						$('.notification').html('Agent mapped successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while mapping agent');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
      			}
   			});
		}
	});	
	
	$('#update_card_status').click(function(){
		validate = card_approve_validation();
		if(validate){
			var group_id = $('#group_id').val();
			var mobile_no = $('#mobile_no').val();
			var agent_payment_config_id = $('#agent_payment_config_id').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/approvecard",  
      			data: {id:agent_payment_config_id,groupid:group_id,mobile_number:mobile_no},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
						$('#approve').modal('hide');
						$('.modal-backdrop').remove();
					  if(data =='1'){
						$('.notification').html('Card aproved successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while approving card');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
					  get_cards();
      			}
   			});
		}
	});

	$('#update_account').click(function(){
		validate = account_validation();
		if(validate){
			var fname = $('#fname').val();
			var lname = $('#lname').val();
			var mail = $('#mail').val();
			var mobile_no = $('#mob').val();
			var userid = $('#userid').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/updateaccount",  
      			data: {id:userid,email:mail,firstname:fname,lastname:lname,mobile_number:mobile_no},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
						$('#editmerchant').modal('hide');
						$('.modal-backdrop').remove();
					  if(data =='1'){
						$('.notification').html('Account updated successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while updating account');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
					  account_mangement();
      			}
   			});
		}
	});

	$('#add_account').click(function(){
		validate = add_account_validation();
		if(validate){
			var fname = $('#add_fname').val();
			var lname = $('#add_lname').val();
			var mail = $('#add_mail').val();
			var mobile_no = $('#add_mob').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/addaccount",  
      			data: {email:mail,firstname:fname,lastname:lname,mobile_number:mobile_no},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
						$('#addmerchant').modal('hide');
						$('.modal-backdrop').remove();
					  if(data =='1'){
						$('.notification').html('Account added successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while adding account');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
					  account_mangement();
      			}
   			});
		}
	});

	$('#groupid').on('change', function (e) {
		$('.limitbox').show();
	});

	$('#limitbtn').click(function(){
		validate = limit_validation();
		if(validate){
			var netbanking_limit = $('#netbanking_limit').val();
			var debit_card_limit = $('#debit_card_limit').val();
			var overall_group_limit = $('#overall_group_limit').val();
			var credit_card_upper_limit = $('#credit_card_upper_limit').val();
			var credit_card_lower_Limit = $('#credit_card_lower_Limit').val();
			var group_id = $('#groupid').val();
			$.ajax({
      			url: "/partnerpay/web/spicejet/merchant/addgrouplimit",  
      			data: {nb:netbanking_limit,dc:debit_card_limit,overall:overall_group_limit,ccul:credit_card_upper_limit,ccll:credit_card_lower_Limit,id:group_id},
     			type: "POST",
      			dataType: "json",
      			success: function(data) {
					$('#netbanking_limit').val("");
					$('#debit_card_limit').val("");
					$('#overall_group_limit').val("");
					$('#credit_card_upper_limit').val("");
					$('#credit_card_lower_Limit').val("");

					$('#netbanking_limit_error').empty();
					$('#netbanking_limit_div').removeClass('has-error');
					
					$('#debit_card_limit_error').empty();
					$('#debit_card_limit_div').removeClass('has-error');

					$('#overall_group_limit_error').empty();
					$('#overall_group_limit_div').removeClass('has-error');

					$('#credit_card_upper_limit_error').empty();
					$('#credit_card_upper_limit_div').removeClass('has-error');

					$('#credit_card_lower_Limit_error').empty();
					$('#credit_card_lower_Limit_div').removeClass('has-error');

					$('#groupid').val("");
					  if(data =='1'){
						$('.notification').html('Agent mapped successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while mapping agent');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
      			}
   			});
		}
	});


</script>

<script>
function validation(){
	validate = true;
	if(!(/^[0-9]+$/i.test($('#agent_id').val()))){
		$('#agent_id_error').empty().html('Improper Agent Id');
		$('#agent_id_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[a-z0-9]+$/i.test($('#agent_password').val()))){
		$('#agent_password_error').empty().html('Improper Agent Password');
		$('#agent_password_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function group_validation(){
	validate = true;
	if(!(/^[A-za-z]+$/i.test($('#group_name').val()))){
		$('#group_name_error').empty().html('Improper Group Name');
		$('#group_name_div').addClass('has-error');
		validate = false;
	}
	if($('#group_status').val()==""){
		$('#group_status_error').empty().html('Please Select Group Status');
		$('#group_status_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function agent_map_validation(){
	validate = true;
	if($('#agency_name').val()==""){
		$('#agency_name_error').empty().html('Company name is required');
		$('#agency_name_div').addClass('has-error');
		validate = false;
	}
	if($('#city').val()==""){
		$('#city_error').empty().html('City is required');
		$('#city_div').addClass('has-error');
		validate = false;
	}
	if($('#state').val()==""){
		$('#state_error').empty().html('State is required');
		$('#state_div').addClass('has-error');
		validate = false;
	}
	if($('#pan_card').val()==""){
		$('#pan_card_error').empty().html('PAN Card is required');
		$('#pan_card_div').addClass('has-error');
		validate = false;
	}
	if($('#agent_status').val()==""){
		$('#agent_status_error').empty().html('Please Select Status');
		$('#agent_status_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function limit_validation(){
	validate = true;
	if(!(/^[0-9]+$/i.test($('#netbanking_limit').val()))){
		$('#netbanking_limit_error').empty().html('Invalid netbanking limit');
		$('#netbanking_limit_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[0-9]+$/i.test($('#debit_card_limit').val()))){
		$('#debit_card_limit_error').empty().html('Invalid debit card limit');
		$('#debit_card_limit_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[0-9]+$/i.test($('#overall_group_limit').val()))){
		$('#overall_group_limit_error').empty().html('Invalid overall group limit');
		$('#overall_group_limit_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[0-9]+$/i.test($('#credit_card_upper_limit').val()))){
		$('#credit_card_upper_limit_error').empty().html('Invalid ctredit card upper limit');
		$('#credit_card_upper_limit_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[0-9]+$/i.test($('#credit_card_lower_Limit').val()))){
		$('#credit_card_lower_Limit_error').empty().html('Invalid credit card lower limit');
		$('#credit_card_lower_Limit_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function card_approve_validation(){
	validate = true;
	if(!(/^\d{10}$/.test($('#mobile_no').val()))){
		$('#mobile_no_error').empty().html('Invalid mobile number');
		$('#mobile_no_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function account_validation(){
	validate = true;
	if(!(/^[A-Za-z]+$/i.test($('#fname').val()))){
		$('#fname_error').empty().html('Invalid first name');
		$('#fname_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[A-Za-z]+$/i.test($('#lname').val()))){
		$('#lname_error').empty().html('Invalid last name');
		$('#lname_div').addClass('has-error');
		validate = false;
	}
	if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test($('#mail').val()))){
		$('#mail_error').empty().html('Invalid email id');
		$('#mail_div').addClass('has-error');
		validate = false;
	}
	if(!(/^\d{10}$/.test($('#mob').val()))){
		$('#mob_error').empty().html('Invalid mobile number');
		$('#mob_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function add_account_validation(){
	validate = true;
	if(!(/^[A-Za-z]+$/i.test($('#add_fname').val()))){
		$('#add_fname_error').empty().html('Invalid first name');
		$('#add_fname_div').addClass('has-error');
		validate = false;
	}
	if(!(/^[A-Za-z]+$/i.test($('#add_lname').val()))){
		$('#add_lname_error').empty().html('Invalid last name');
		$('#add_lname_div').addClass('has-error');
		validate = false;
	}
	if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test($('#add_mail').val()))){
		$('#add_mail_error').empty().html('Invalid email id');
		$('#add_mail_div').addClass('has-error');
		validate = false;
	}
	if(!(/^\d{10}$/.test($('#add_mob').val()))){
		$('#add_mob_error').empty().html('Invalid mobile number');
		$('#add_mob_div').addClass('has-error');
		validate = false;
	}
	return validate;
}

function approve(id){
	$('#agent_id_error').empty();
	$('#agent_id_div').removeClass('has-error');
	$('#agent_password_error').empty();
	$('#agent_password_div').removeClass('has-error');
	$('#agent_details_id').val(id);
}

function edit_account(id){
	$('#userid').val(id);
}

function approve_card(id){
	$('#group_id_error').empty();
	$('#group_id_div').removeClass('has-error');
	$('#mobile_no_error').empty();
	$('#mobile_no_div').removeClass('has-error');
	$('#ungrouped').addClass('hidden');
	$('#grouped').addClass('hidden');
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/checkagentingroup",  
      		data: {agent_payment_config_id: id},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
            		if(data.status =='1'){
						$('#grouped').removeClass('hidden');
						$('#card_agent').empty().html(data.AGENT_ID);
						$('#group_id').val(data.GROUP_ID);
						$('#agent_payment_config_id').val(id);
					  }else{
						$('#ungrouped').removeClass('hidden');
					  }
      		}
   		})
}
function map_agent(group_id,group_name){
	$('#group_id_map').val(group_id);
	$('#group_name_map').val(group_name);
}

$('.agent_list_search').change(function(){
	getdata();
})

$('.card_list_search').change(function(){
	get_cards();
})

function reject(id){
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/reject",  
      		data: {agent_details_id: id},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
            	if(data =='1'){
						$('.notification').html('Agent rejected successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while rejecting agent');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
					  getdata();
      		}
   		})
}

function reject_card(id){
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/rejectcard",  
      		data: {AGENT_PAYMENT_CONFIG_ID: id},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
            	if(data =='1'){
						$('.notification').html('Card rejected successfully');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }else{
						$('.notification').html('Error while rejecting card');
					  	$('.notification').removeClass('hidden');
					  	setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
					  }
					  get_cards();
      		}
   		})
}

function next(){
	var current_page = parseInt($('.next').attr('id'));
	var last_page = $('.next').data('max');
	var next_page = current_page+1;
	if(next_page<=last_page){
		$('.next').attr('id',next_page);
		$('.previous').attr('id',next_page);
		if(next_page == last_page){
			$('.next').addClass('hidden');
		}
		if(next_page >1){
			$('.previous').removeClass('hidden');
		}
		getdata();
	}
}

function previous(){
	var current_page = $('.previous').attr('id');
	if((parseInt(current_page)-1) != 0){
		var previous_page = current_page-1;
		$('.next').attr('id',previous_page);
		$('.previous').attr('id',previous_page);
		$('.next').removeClass('hidden');
		if(previous_page == 1){
			$('.previous').addClass('hidden');
		}
		getdata();
	}
}

function next_group(){
	var current_page = parseInt($('.next_group').attr('id'));
	var last_page = $('.next_group').data('max');
	var next_page = current_page+1;
	if(next_page<=last_page){
		$('.next_group').attr('id',next_page);
		$('.previous_group').attr('id',next_page);
		if(next_page == last_page){
			$('.next_group').addClass('hidden');
		}
		if(next_page >1){
			$('.previous_group').removeClass('hidden');
		}
		get_group();
	}
}

function previous_group(){
	var current_page = $('.previous_group').attr('id');
	if((parseInt(current_page)-1) != 0){
		var previous_page = current_page-1;
		$('.next_group').attr('id',previous_page);
		$('.previous_group').attr('id',previous_page);
		$('.next_group').removeClass('hidden');
		if(previous_page == 1){
			$('.previous_group').addClass('hidden');
		}
		get_group();
	}
}

function next_card(){
	var current_page = parseInt($('.next_card').attr('id'));
	var last_page = $('.next_card').data('max');
	var next_page = current_page+1;
	if(next_page<=last_page){
		$('.next_card').attr('id',next_page);
		$('.previous_card').attr('id',next_page);
		if(next_page == last_page){
			$('.next_card').addClass('hidden');
		}
		if(next_page >1){
			$('.previous_card').removeClass('hidden');
		}
		get_cards();
	}
}

function previous_card(){
	var current_page = $('.previous_card').attr('id');
	if((parseInt(current_page)-1) != 0){
		var previous_page = current_page-1;
		$('.next_card').attr('id',previous_page);
		$('.previous_card').attr('id',previous_page);
		$('.next_card').removeClass('hidden');
		if(previous_page == 1){
			$('.previous_card').addClass('hidden');
		}
		get_cards();
	}
}

function next_account(){
	var current_page = parseInt($('.next_account').attr('id'));
	var last_page = $('.next_account').data('max');
	var next_page = current_page+1;
	if(next_page<=last_page){
		$('.next_account').attr('id',next_page);
		$('.previous_account').attr('id',next_page);
		if(next_page == last_page){
			$('.next_account').addClass('hidden');
		}
		if(next_page >1){
			$('.previous_account').removeClass('hidden');
		}
		account_mangement();
	}
}

function previous_account(){
	var current_page = $('.previous_account').attr('id');
	if((parseInt(current_page)-1) != 0){
		var previous_page = current_page-1;
		$('.next_account').attr('id',previous_page);
		$('.previous_account').attr('id',previous_page);
		$('.next_account').removeClass('hidden');
		if(previous_page == 1){
			$('.previous_account').addClass('hidden');
		}
		account_mangement();
	}
}

function getdata(){
	var company_name= $('#company_search').val();
	var fname= $('#fname_search').val();
	var lname= $('#lname_search').val();
	var staff_name = fname+" "+lname;
	if(staff_name == ' '){
		staff_name = "";
	}
	var phone= $('#phone_search').val();
	var email= $('#email_search').val();
	var current_page = $('.next').attr('id');
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/getagents",  
      		data: {company:company_name,name:staff_name,phone_number:phone,mail_id:email,page:current_page},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  table_data = "";
            	if(data['count_data'][0]['total']>0){
					$.each(data['list_data'],function (index,value){
					var name = new Array(); 
					name = value['STAFF_NAME'].split(' ');
					if(name[1]==undefined){
						name[1] = "";
					}
					table_data = table_data+'<tr><td class="idnum">'+(parseInt(index)+1)+'</td><td>'+value["COMPANY_NAME"]+'</td><td>'+name[0]+'</td><td>'+name[1]+'</td><td>'+value["PHONE"]+'</td><td>'+value["EMAIL"]+'</td><td><div class="btnAction"><a onClick="approve('+value["AGENT_DETAILS_ID"]+')" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Approve</a><a href="javascript:void(0)" onClick="reject('+value["AGENT_DETAILS_ID"]+')" class="btn btn-danger">Reject</a></div></td></tr>';
								});
				}else{
					table_data = "<tr><td colspan='7'> No data Found</td></tr>"
				}
				$('#agent_list tbody').empty().append(table_data);
				if(parseInt(data['count_data'][0]['total']) % 2 == 0){
                    var total_pages =parseInt(data['count_data'][0]['total'])/2;
				} else {
                    var total_pages = Math.floor(parseInt(data['count_data'][0]['total'])/2)+1; 
                }
				$('.next').attr('data-max',total_pages);
      		}
   		})
}

function get_group(){
	var current_page = $('.next_group').attr('id');
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/getgroups",  
      		data: {page:current_page},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  table_data = "";
            	if(data['count_data'][0]['total']>0){
					$.each(data['list_data'],function (index,value){
						var date = new Date(value['CREATED_DATE'] * 1000);
						if(value['STATUS'] == '1'){
							var checked="checked";
						}else{
							var checked="";
						}
					table_data = table_data+'<tr><td>'+(parseInt(index)+1)+'</td><td>'+value['GROUP_NAME']+'</td><td><div class="onoffswitch"><input type="checkbox"  id="onoffswitch'+(parseInt(index)+1)+'" name="group_status_'+value['AGNET_GROUP_ID']+'" onClick="Change_group_status('+value['AGNET_GROUP_ID']+')" class="onoffswitch-checkbox" '+checked+'><label class="onoffswitch-label" for="onoffswitch'+(parseInt(index)+1)+'"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div></td><td>'+date.getDate()+'-' + (date.getMonth()+1) + '-'+date.getFullYear()+'</td><td class="action"><div class="bbox"><a onClick="map_agent('+value['AGNET_GROUP_ID']+',\''+value['GROUP_NAME']+'\')" data-toggle="modal" data-target="#mapagency" title="Edit"><span class="glyphicon glyphicon-cog"></span></a></div></td></tr>';
								});
				}else{
					table_data = "<tr><td colspan='5'> No data Found</td></tr>"
				}
				$('#group tbody').empty().append(table_data);
				if(parseInt(data['count_data'][0]['total']) % 2 == 0){
                    var total_pages =parseInt(data['count_data'][0]['total'])/2;
				} else {
                    var total_pages = Math.floor(parseInt(data['count_data'][0]['total'])/2)+1; 
                }
				$('.next_group').attr('data-max',total_pages);
      		}
   		})
}

function Change_group_status(group_id){
 var status = $('input[name="group_status_'+group_id+'"]:checked').length;
 $.ajax({
      		url: "/partnerpay/web/spicejet/merchant/groupstatus",  
      		data: {id:group_id,group_status:status},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  console.log(data)
				  if(data == '1'){
					  $('.notification').html('Group status updated successfully');
					  $('.notification').removeClass('hidden');
					  setTimeout(function(){
                    		$('.notification').empty();
                    		$('.notification').addClass('hidden');
                		}, 2000);
				  }
      		}
   		})
}

function set_limit(){
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/getgrouplist",
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  option_data='<option value="">Select Group ID</option>';
				  $.each(data,function(index,value){
					option_data = option_data+'<option value="'+value['AGNET_GROUP_ID']+'">'+value['GROUP_NAME']+'</option>'
				  })
				  $('#groupid').empty().append(option_data);

      		}
   		})
}

function get_cards(){
	var current_page = $('.next_card').attr('id');
	var agent_id_search= $('#agent_id_search').val();
	var card_email_search= $('#card_email_search').val();
	var payment_instrument_search= $('#payment_instrument_search').val();
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/getcards",  
      		data: {page:current_page,agent_id:agent_id_search,email:card_email_search,payment_search:payment_instrument_search},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  table_data = "";
            	if(data['count_data'][0]['total']>0){
					$.each(data['list_data'],function (index,value){
						// var date = new Date(value['CREATED_DATE'] * 1000);
						table_data = table_data+'<tr><td class="idnum">'+(parseInt(index)+1)+'</td><td>'+value['AGENT_ID']+'</td><td>'+value['EMAIL']+'</td><td>'+value['CARD_NUMBER']+'</td><td class="action"><div class="bbox bbox4"><a title="Approve" onClick="approve_card('+value["AGENT_PAYMENT_CONFIG_ID"]+')" data-toggle="modal" data-target="#approve"><span class="glyphicon glyphicon-ok"></span></a><a title="Reject" onClick="reject_card('+value["AGENT_PAYMENT_CONFIG_ID"]+')" ><span class="glyphicon glyphicon-remove"></span></a></div></td></tr>';
								});
				}else{
					table_data = "<tr><td colspan='5'> No data Found</td></tr>"
				}
				$('#card tbody').empty().append(table_data);
				if(parseInt(data['count_data'][0]['total']) % 2 == 0){
                    var total_pages =parseInt(data['count_data'][0]['total'])/2;
				} else {
                    var total_pages = Math.floor(parseInt(data['count_data'][0]['total'])/2)+1; 
                }
				$('.next_card').attr('data-max',total_pages);
      		}
   		})
}

function account_mangement(){
	var current_page = $('.next_account').attr('id');
	$.ajax({
      		url: "/partnerpay/web/spicejet/merchant/getaccounts",  
      		data: {page:current_page},
     		type: "POST",
      		dataType: "json",
      		success: function(data) {
				  table_data = "";
            	if(data['count_data'][0]['total']>0){
					$.each(data['list_data'],function (index,value){
						var date = new Date(value['CREATED_ON'] * 1000);
						table_data = table_data+'<tr><td class="idnum">'+(parseInt(index)+1)+'</td><td>'+value['FIRST_NAME']+' '+value['LAST_NAME']+'</td><td>'+value['EMAIL']+'</td><td>Merchant</td><td>'+date.getDate()+'-' + (date.getMonth()+1) + '-'+date.getFullYear()+'</td><td class="action"><div class="bbox"><a onClick="edit_account('+value['USER_ID']+')" data-toggle="modal" data-target="#editmerchant" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a><a title="Delete"><span class="glyphicon glyphicon-trash"></span></a></div></td></tr>';
								});
				}else{
					table_data = "<tr><td colspan='5'> No data Found</td></tr>"
				}
				$('#account tbody').empty().append(table_data);
				if(parseInt(data['count_data'][0]['total']) % 2 == 0){
                    var total_pages =parseInt(data['count_data'][0]['total'])/2;
				} else {
                    var total_pages = Math.floor(parseInt(data['count_data'][0]['total'])/2)+1; 
                }
				$('.next_account').attr('data-max',total_pages);
      		}
   		})
}
</script>


</body>
</html>
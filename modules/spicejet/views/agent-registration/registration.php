<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Partnerpay-Spicejet</title>
	<link rel="shortcut icon" type="image/x-icon" href="resources/images/favicon.ico">
	<link rel="stylesheet" href="resources/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="resources/css/font-awesome.css" type="text/css" />
	<link rel="stylesheet" href="resources/css/custom.css" type="text/css" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

	<!--<div class="header navbar-fixed-top">
		<nav id="mainNav" class="navbar navbar-default ">
			<div class="container tophead">
				
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Menu</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">
						<span class="brandimg"><img alt="Partnerpay" src="resources/images/partnerpay-logo.png" /></span>
						<span class="brandimg bank-brand"><img alt="banklogo" src="resources/images/brandlogo.png" /></span>
					</a>
				</div> 
				
			</div> 
		</nav>
	</div>-->

	<div class="wrapper">
		<div class="container">
			<div class="page-header">
				<h4>Travel agent registration form details</h4>
				<!-- <div class="fieldstx">Fields with <span>*</span> are required.</div> -->
			</div>
			<form id="frmdata" name="frmdata" action="" method="post" class="form" role="form">
			<div class="form-container form1">
				<h5>Company Contact Details</h5>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'company_class'>
							<input type="text" class="form-control" placeholder="Company / Agency Name" id = 'company_name_id' name ='company_name_id'>
							<div class="help-block" id = 'company_name_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'business_reg_class'>
							<input type="text" class="form-control" placeholder="Business Registration No." id = 'business_reg_num_id' name ='business_reg_num_id'>
							<div class="help-block" id = 'business_reg_num_error'></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'address_value_class'>
							<textarea class="form-control" rows="2" placeholder="Address" id = 'address_value_id' name = 'address_value_id'></textarea>
							<div class="help-block" id = 'address_value_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<label class="req">Legal Status (please select)</label>
						<div class="form-group" id="chk_status_class">
							<label class="checkbox-inline">
								<input type="checkbox" id="status1" value="Sole Proprietorship" name="status_value[]" class="check_status"> Sole Proprietorship
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="status2" value="Limited Co" name="status_value[]" class="check_status"> Limited Co.
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" id="status3" value="Partnership Firm" name="status_value[]" class="check_status"> Partnership Firm
							</label>
							<div class="help-block" id ='check_status_error' ></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'city_class'>
							<input type="text" class="form-control" placeholder="City" id= 'city_id' name = 'city_id'>
							<div class="help-block" id = 'city_id_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'state_class'>
							<input type="text" class="form-control" placeholder="State" id = 'state_id' name = 'state_id'>
							<div class="help-block"  id = 'state_id_error'></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id='country_class'>
							<input type="text" class="form-control" placeholder="Country" id = 'country_id' name='country_id'>
							<div class="help-block" id = 'country_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'pin_class'>
							<input type="text" class="form-control" placeholder="Pin Code" id = 'pin_id' name='pin_id'>
							<div class="help-block" id = 'pin_error'></div>
						</div>
					</div>
				</div>
				<hr>
				<h5>Personal Details</h5>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'airport_class'>
							<input type="text" class="form-control" placeholder="Nearest Airport" id = 'airport_id' name='airport_id'>
							<div class="help-block" id = 'airport_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'email_class'>
							<input type="text" class="form-control" placeholder="E-mail" id = 'email_id' name='email_id'>
							<div class="help-block" id = 'email_id_error'></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'phone_class'>
							<input type="text" class="form-control" placeholder="Phone" id = 'phone_id' name='phone_id'>
							<div class="help-block" id = 'phone_id_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'fax_class'>
							<input type="text" class="form-control" placeholder="Fax" id = 'fax_id' name='fax_id'>
							<div class="help-block" id = 'fax_id_error'></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group">
							<input type="button" class="btn btn-primary sm-btn" id="form1btn" value="Next">
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-container form2">
				<h5>Affiliation Details / PAN (Permanent Account No.)</h5>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'permant_account_class'>
							<input type="text" class="form-control" placeholder="IATA / TAAI / TAFI / IATO No." id = 'permant_account_id' name ='permant_account_id'>
							<div class="help-block" id = 'permant_account_id_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req req" id = 'pan_number_class'>
							<input type="text" class="form-control" placeholder="PAN No." id= 'pan_number_id' name ='pan_number_id'>
							<div class="help-block" id = 'pan_number_error'></div>
						</div>
					</div>
				</div>
				<hr>
				<h5>Proprietor/Partner/CEO/MD</h5>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'partner_id_class'>
							<input type="text" class="form-control" placeholder="Name" id ='partner_id' name ='partner_id'>
							<div class="help-block" id = 'partner_id_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req req" id = 'partner_position_class'>
							<input type="text" class="form-control" placeholder="Position" id = 'partner_position_id' name ='partner_position_id'>
							<div class="help-block" id = 'partner_position_error'></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'partner_contact_class'>
							<input type="text" class="form-control" placeholder="Phone/Mobile" id = 'partner_contact_id' name ='partner_contact_id'>
							<div class="help-block" id = 'partner_contact_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'partner_email_class'>
							<input type="text" class="form-control" placeholder="E-mail" id = 'partner_email_id' name ='partner_email_id'>
							<div class="help-block" id = 'partner_email_error'></div>
						</div>
					</div>
				</div>
				<hr>
				<h5>Authorised Booking Staff contact details</h5>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'staff_id_class'>
							<input type="text" class="form-control" placeholder="Name" id = 'staff_id' name ='staff_id'>
							<div class="help-block" id = 'staff_id_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'staff_position_class'>
							<input type="text" class="form-control" placeholder="Position" id = 'staff_position_id' name ='staff_position_id'>
							<div class="help-block" id = 'staff_position_error'></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'staff_contact_class'>
							<input type="text" class="form-control" placeholder="Phone/Mobile" id = 'staff_contact_id' name ='staff_contact_id'>
							<div class="help-block" id = 'staff_contact_error'></div>
						</div>
					</div>
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group req" id = 'staff_email_class'>
							<input type="text" class="form-control" placeholder="E-mail" id = 'staff_email_id' name='staff_email_id'>
							<div class="help-block" id = 'staff_email_error'></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group">
							<input type="button" class="btn btn-warning sm-btn" id="back1btn" value="Back">
							<input type="button" class="btn btn-primary sm-btn" id="form2btn" value="Next">
						</div>
					</div>
				</div>
			</div>
				
			<div class="form-container form3">	
				<h5>Payment Method<span class="req"></span></h5>
				<div class="cardblockWrap">
					<div class="cardblock">
						<div class="row">
							<div class="col-sm-6 col-md-5 col-lg-4">
								<div class="form-group req" id="pay_class_0">
									<div class="radio">
									<label class="radio-inline">
										<input type="radio" name="pay_0" id="pay_0" value="Credit Card" class="radioBtn" checked>Credit Card
									</label>
									<label class="checkbox-inline">
										<input type="radio" name="pay_0" id="pay_0" value="Debit Card" class="radioBtn"> Debit Card
									</label>
									<label class="checkbox-inline">
										<input type="radio" name="pay_0" id="pay_0" value="Netbanking" class="radioBtn"> Netbanking
									</label>
									</div>
									<div class="help-block " id = 'pay_error_0'></div>
								</div>
							</div>
						</div>
						<div class="cardtype_0">
							<div class="row">
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group req" id="select_card_type_class_0">
										<select class="form-control" id = 'select_card_type_0' name='select_card_type_0'>
											<option value="">Select Card Type</option>
											<option value="International">International </option>
											<option value="Domestic">Domestic</option>
										</select>
										<div class="help-block" id = 'select_card_type_error_0'></div>
									</div>
								</div>
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group req" id = "card_number_class_0">
										<input type="text" class="form-control" placeholder="Card Number" id = 'card_number_0' name='card_number_0'>
										<div class="help-block" id = 'card_number_error_0'></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group req" id = "card_nick_name_class_0">
										<input type="text" class="form-control" placeholder="Card Nick Name" id = 'card_nick_name_0' name = 'card_nick_name_0'>
										<div class="help-block" id = 'card_nick_name_error_0'></div>
									</div>
								</div>
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group req" id= "card_exp_month_class_0">
												<select class="form-control" id = 'card_exp_month_0' name = 'card_exp_month_0'>
													<option value="">Expiry Month</option>
													<option value="01">01</option>
													<option value="02">02</option>
													<option value="03">03</option>
													<option value="04">04</option>
													<option value="05">05</option>
													<option value="06">06</option>
													<option value="07">07</option>
													<option value="08">08</option>
													<option value="09">09</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
												</select>
												<div class="help-block" id = 'card_exp_month_error_0'></div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group req" id= "card_exp_year_class_0">
												<select class="form-control" id = 'card_exp_year_0' name = 'card_exp_year_0'>
													<option value=" ">Expiry Year</option>
													<option value="2018">2018</option>
													<option value="2019">2019</option>
													<option value="2020">2022</option>
												</select>
												<div class="help-block" id = 'card_exp_year_error_0'></div>
											</div>
										</div>
									</div>
								</div>
							</div>		
						
							<div class="row">
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group req" id = "cvv_class_0">
										<input type="text" class="form-control" placeholder="CVV number" id = 'cvv_number_0' name='cvv_number_0'>
										<div class="help-block" id = 'cvv_number_error_0'></div>
									</div>
								</div>
							</div>									
							<!-- <div class="form-group req">
								<input type="button" class="btn btn-primary" id="addcardbtn" value="Add Card">
							</div> -->
						</div>	
						
						<div class="banktype_0" style="display:none;">
							<div class="row">
								<div class="col-sm-6 col-md-5 col-lg-4">
									<div class="form-group req" id = "select_bank_class_0">
									<select class="form-control" id = 'select_bank_0' name = 'select_bank_0'>
										<option value="">Select Bank</option>
									</select>
									<div class="help-block" id = 'select_bank_error_0'></div>
									</div>
								</div>
							</div>
										
							<!-- <div class="form-group req">
								<input type="button" class="btn btn-primary" id="addbankbtn" value="Add Bank">
							</div> -->
						</div>
						
						
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<a href="javascript:void(0)" id="addbtn"><span class="glyphicon glyphicon-plus"></span> Add another payment method</a>
						</div>
					</div>
				</div>

				<hr>
				<div class="row">
					<div class="col-sm-6 col-md-5 col-lg-4">
						<div class="form-group">
							<input type="button" class="btn btn-warning sm-btn" id="back2btn" value="Back">
							<input type="button" class="btn btn-primary sm-btn" id="submitform" value="Submit">
							<input type="hidden" class="form-control" id = 'save_card_count' name='save_card_count'>
						</div>
					</div>
					<!--<div class="col-sm-6 col-md-5 col-lg-4">
					    <div id='save_card_failed' name = "save_card_failed"></div>
					</div>-->
				</div>
			</div>
			</form>
		</div>
	</div>



	<div class="footer">
		<div class="container copyright">
			<p>Copyright © 2018 by airpay.</p>
		</div>
	</div>

	<!-- jQuery -->
	<script type="text/javascript" src="resources/js/jquery.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script type="text/javascript" src="resources/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="resources/js/bootstrap.file-input.js"></script>
	<script type="text/javascript" src="resources/js/custom.js"></script>

	<script type="text/javascript">
		$("#submitform").click(function () {
			var isValidate = true;
			var card_type_count = parseInt(document.getElementById('save_card_count').value);
			
			//for intialization error
			for (var j = 0; j <= card_type_count; j++) {

				//for select bank 
				$("#select_bank_error_" + j).html('');
				$("#select_bank_class_" + j).removeClass("has-error");
				
				//for select card type
				$("#select_card_type_error_" + j).html('');
				$("#select_card_type_class_" + j).removeClass("has-error");
				
				//for card exp month
				$("#card_exp_month_error_" + j).html('');
				$("#card_exp_month_class_" + j).removeClass("has-error");
				
				//for card exp year
				$("#card_exp_year_error_" + j).html('');
				$("#card_exp_year_class_" + j).removeClass("has-error");

				//for card nick name
				$("#card_nick_name_error_" + j).html('');
				$("#card_nick_name_class_" + j).removeClass("has-error");

				//for card number

				$("#card_number_error_" + j).html('');
				$("#card_number_class_" + j).removeClass("has-error");
				
				//for card CVV
				$("#cvv_number_error_" + j).html('');
				$("#cvv_class_" + j).removeClass("has-error");
				

				
			}
			
			//validation loop
			 for (var i = 0; i <= card_type_count; i++) {
				  if($("#pay_" + i+":checked").val() == "Netbanking")
				  {
					 if($.trim($("#select_bank_" + i).val()) == "")
					 {
						 $("#select_bank_error_" + i).html('Please select bank');
						 $("#select_bank_class_" + i).addClass("has-error");
						 isValidate = false;
					 }
				  }
				  else
				  {
					 if($.trim($("#select_card_type_" + i).val()) == "")
					 {
						 $("#select_card_type_error_" + i).html('Please select card type');
						 $("#select_card_type_class_" + i).addClass("has-error");
						 isValidate = false;
					 }
					 
					 //expire month validation
					 if($.trim($("#card_exp_month_" + i).val()) == "")
					 {
						 $("#card_exp_month_error_" + i).html('Please select month');
						 $("#card_exp_month_class_" + i).addClass("has-error");
						 isValidate = false;
					 }
					 else{
					 	if (parseInt($("#card_exp_month_" + i).val()) < parseInt(1) || parseInt($("#card_exp_month_" + i).val()) > parseInt(12)) {
        					 	$("#card_exp_month_error_" + i).html('Invalid expiry month.');
						 		$("#card_exp_month_class_" + i).addClass("has-error");
						 
        					    isValidate = false;
       						
    					}
					 }
					 //expire year validation
					 if($.trim($("#card_exp_year_" + i).val()) == "")
					 {
						 $("#card_exp_year_error_" + i).html('Please select year');
						 $("#card_exp_year_class_" + i).addClass("has-error");
						 isValidate = false;
					 }

					 //for card nick name validation
					 if($.trim($("#card_nick_name_" + i).val()) == "")
					 {
						 $("#card_nick_name_error_" + i).html('Please enter card nick name');
						 $("#card_nick_name_class_" + i).addClass("has-error");
						 isValidate = false;
					 }
					 else{
					 	var reg = /^[A-Za-z\d\s]+$/;
					 	var card_nickname = '';
					 	card_nickname = $("#card_nick_name_" + i).val();
    					if (!reg.test(card_nickname)){
    						 $("#card_nick_name_error_" + i).html('Please enter valid card nickname.');
						 	 $("#card_nick_name_class_" + i).addClass("has-error");
						     isValidate = false;
        					
    					}
    					else
    					{
        					if(card_nickname.length<1)
        					{
        						  $("#card_nick_name_error_" + i).html('Card nickname should be minimum 1 character.');
						 		  $("#card_nick_name_class_" + i).addClass("has-error");
						          isValidate = false;
           						
        					}
    					}
					 }
					/* card_number_
				card_number_class_
				card_number_error_*/

					 // for card number
					 var card_number = '';
					 card_number = $("#card_number_" + i).val();
					 if(card_number == '') {
					 	$("#card_number_error_" + i).html('Please enter card number.');
						$("#card_number_class_" + i).addClass("has-error");
						isValidate = false;
						}
						else{
							var t = card_number.split(" ").join("");
							var n = /^\d+$/;
							 if (!t.match(n)) {
        						$("#card_number_error_" + i).html('Please enter valid card number.');
								$("#card_number_class_" + i).addClass("has-error");
								isValidate = false;
    						}
    						else{
    							var r = GetCardType(t);
    							if (typeof r === "undefined") {
            						$("#card_number_error_" + i).html('Invalid card number.');
									$("#card_number_class_" + i).addClass("has-error");
									isValidate = false;
        						}
        						else{
									/*alert('here i am');
									return false;*/
        							 var iNew = r.split("~");
            						 var s = iNew[0];
            						 var o = iNew[1];
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
											
                							$("#card_number_error_" +i).html('Card Number should be 16 digits.');
											$("#card_number_class_" +i).addClass("has-error");
											isValidate = false;
                                        	
                                       }
                                    }
        						}
    						}
						}
						// validation for card CVV number
						/*if($.trim($("#cvv_number_" + i).val()) == "")
						{
							$("#cvv_number_error_" + i).html('Please enter card cvv.');
							$("#cvv_class_" + i).addClass("has-error");
							isValidate = false;
						}
						else
						{
							var reg = /^\d+$/;
					 	    var card_CSV = '';
					 	    card_CSV = $("#card_nick_name_" + i).val();
    					    if (!reg.test(card_CSV)){
    						 $("#cvv_number_error_" + i).html('Please enter digits only.');
						 	 $("#cvv_class_" + i).addClass("has-error");
						     isValidate = false;
        					
    					    }
							
						}*/
					 
				  }
				  
				}
				//alert(isValidate);
				//return false;
				if(isValidate)
				{
					// call ajax
					$.ajax({
						url: "/partnerpay/web/spicejet/agentregistration/savedata",  
						data: $("#frmdata").serialize(),
						type: "POST",
						dataType: "json",
					
						success: function(data) {
							if(data.Result == "success")
							{
								alert(data.Message);
								return false;
							}
							else
							{
								//alert(data.Message);
								$.each(data.Message, function (ind, vl) {
                                   //$('.save_card_failed').append(vl);
								   //alert(ind);
								   alert(vl);
                                });
								//$('.save_card_failed').show();
								//console.log(data.Message);
								return false;
							}
				
						}
					})
					
				}
			
		});
		
		$('#form1btn').click(function () {
			//for fisrt tab
			var rt_type = true;
			
			//intialization values
			document.getElementById('company_name_error').innerHTML = '';
			document.getElementById('business_reg_num_error').innerHTML = '';
			document.getElementById('address_value_error').innerHTML = '';
			document.getElementById('city_id_error').innerHTML = '';
			document.getElementById('state_id_error').innerHTML = '';
			document.getElementById('country_error').innerHTML = '';
			document.getElementById('pin_error').innerHTML = '';
			document.getElementById('check_status_error').innerHTML = '';
			document.getElementById('airport_error').innerHTML = '';
			document.getElementById('email_id_error').innerHTML = '';
			document.getElementById('phone_id_error').innerHTML = '';
			document.getElementById('fax_id_error').innerHTML = '';
			
			//romve class
			document.getElementById("company_class").classList.remove("has-error");
			document.getElementById("business_reg_class").classList.remove("has-error");
			document.getElementById("address_value_class").classList.remove("has-error");
			document.getElementById("chk_status_class").classList.remove("has-error");
			document.getElementById("city_class").classList.remove("has-error");
			document.getElementById("state_class").classList.remove("has-error");
			document.getElementById("country_class").classList.remove("has-error");
			document.getElementById("pin_class").classList.remove("has-error");
			document.getElementById("airport_class").classList.remove("has-error");
			document.getElementById("email_class").classList.remove("has-error");
			document.getElementById("phone_class").classList.remove("has-error");
			document.getElementById("fax_class").classList.remove("has-error");
			
			//for company name
			if (document.getElementById('company_name_id').value.trim() == "") {
				document.getElementById('company_name_error').innerHTML = 'Please enter company name.';
				document.getElementById("company_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[0-9a-zA-Z]+$/;
				if (!reg.test(document.getElementById('company_name_id').value.trim())) {
					document.getElementById('company_name_error').innerHTML = 'Please enter valid company name.';
					document.getElementById("company_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for business reg number
			if (document.getElementById('business_reg_num_id').value.trim() == "") {
				document.getElementById('business_reg_num_error').innerHTML = 'Please enter business registration no.';
				document.getElementById("business_reg_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[0-9a-zA-Z]+$/;
				if (!reg.test(document.getElementById('business_reg_num_id').value.trim())) {
					document.getElementById('business_reg_num_error').innerHTML = 'Please enter valid business registration no.';
					document.getElementById("business_reg_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for address 
			if (document.getElementById('address_value_id').value.trim() == "") {
				document.getElementById('address_value_error').innerHTML = 'Please enter address.';
				document.getElementById("address_value_class").classList.add("has-error");
				rt_type = false;
			}
			//for city
			
			if (document.getElementById('city_id').value.trim() == "") {
				document.getElementById('city_id_error').innerHTML = 'Please enter city.';
				document.getElementById("city_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('city_id').value.trim())) {
					document.getElementById('city_id_error').innerHTML = 'Please enter valid city.';
					document.getElementById("city_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//state
			if (document.getElementById('state_id').value.trim() == "") {
				document.getElementById('state_id_error').innerHTML = 'Please enter state.';
				document.getElementById("state_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('state_id').value.trim())) {
					document.getElementById('state_id_error').innerHTML = 'Please enter valid state.';
					document.getElementById("state_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for country
			if (document.getElementById('country_id').value.trim() == "") {
				document.getElementById('country_error').innerHTML = 'Please enter country.';
				document.getElementById("country_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('country_id').value.trim())) {
					document.getElementById('country_error').innerHTML = 'Please enter valid country.';
					document.getElementById("country_class").classList.add("has-error");
					rt_type = false;
				}
			}
			
			//for pincode
			if (document.getElementById('pin_id').value.trim() == "") {
				document.getElementById('pin_error').innerHTML = 'Please enter pincode.';
				document.getElementById("pin_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[0-9]{6,6}$/;
				if (!reg.test(document.getElementById('pin_id').value.trim())) {
					document.getElementById('pin_error').innerHTML = 'Please enter valid pincode.';
					document.getElementById("pin_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for status check
			var count = 0;
			$(".check_status").each(function () {
				if ($(this).is(':checked')) {
					count++;
				}
			});
			if (count == 0) {
				document.getElementById('check_status_error').innerHTML = 'Please select any one status.';
				document.getElementById("chk_status_class").classList.add("has-error");
				rt_type = false;
			}
			//airport name
			if (document.getElementById('airport_id').value.trim() == "") {
				document.getElementById('airport_error').innerHTML = 'Please enter airport name.';
				document.getElementById("airport_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('airport_id').value.trim())) {
					document.getElementById('airport_error').innerHTML = 'Please enter valid airport name.';
					document.getElementById("airport_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//email id
			if (document.getElementById('email_id').value.trim() == "") {
				document.getElementById('email_id_error').innerHTML = 'Please enter email id.';
				document.getElementById("email_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
				if (!reg.test(document.getElementById('email_id').value.trim())) {
					document.getElementById('email_id_error').innerHTML = 'Please enter valid email id.';
					document.getElementById("email_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//phone number
			if (document.getElementById('phone_id').value.trim() == "") {
				document.getElementById('phone_id_error').innerHTML = 'Please enter phone numner.';
				document.getElementById("phone_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\d{10}$/;
				if (!reg.test(document.getElementById('phone_id').value.trim())) {
					document.getElementById('phone_id_error').innerHTML = 'Please enter valid phone number.';
					document.getElementById("phone_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//fax number
			if (document.getElementById('fax_id').value.trim() == "") {
				document.getElementById('fax_id_error').innerHTML = 'Please enter fax numner.';
				document.getElementById("fax_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				//var reg = /^\+?[0-9]{7,}$/;
				var reg = /^\d{7}$/;
				if (!reg.test(document.getElementById('fax_id').value.trim())) {
					document.getElementById('fax_id_error').innerHTML = 'Please enter valid fax number.';
					document.getElementById("fax_class").classList.add("has-error");
					rt_type = false;
				}
			}
			
			if(rt_type)
			{
				$('.form2').show();
				$('.form1').hide();
			}	
		});
		
		$('#form2btn').click(function () {
			//for second tab
			var rt_type = true;
			//intialization values
			document.getElementById('permant_account_id_error').innerHTML = '';
			document.getElementById('pan_number_error').innerHTML = '';
			document.getElementById('partner_id_error').innerHTML = '';
			document.getElementById('partner_position_error').innerHTML = '';
			document.getElementById('partner_contact_error').innerHTML = '';
			document.getElementById('partner_email_error').innerHTML = '';
			document.getElementById('staff_id_error').innerHTML = '';
			document.getElementById('staff_position_error').innerHTML = '';
			document.getElementById('staff_contact_error').innerHTML = '';
			document.getElementById('staff_email_error').innerHTML = '';
			
			
			//romve class
			
			document.getElementById("permant_account_class").classList.remove("has-error");
			document.getElementById("pan_number_class").classList.remove("has-error");
			document.getElementById("partner_id_class").classList.remove("has-error");
			document.getElementById("partner_position_class").classList.remove("has-error");
			document.getElementById("partner_contact_class").classList.remove("has-error");
			document.getElementById("partner_email_class").classList.remove("has-error");
			document.getElementById("staff_id_class").classList.remove("has-error");
			document.getElementById("staff_position_class").classList.remove("has-error");
			document.getElementById("staff_contact_class").classList.remove("has-error");
			document.getElementById("staff_email_class").classList.remove("has-error");
			
			//for permant account number
			if (document.getElementById('permant_account_id').value.trim() == "") {
				document.getElementById('permant_account_id_error').innerHTML = 'Please enter permanent account No.';
				document.getElementById("permant_account_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[0-9a-zA-Z]+$/;
				if (!reg.test(document.getElementById('permant_account_id').value.trim())) {
					document.getElementById('permant_account_id_error').innerHTML = 'Please enter valid permanent account No.';
					document.getElementById("permant_account_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for pan card number
			if (document.getElementById('pan_number_id').value.trim() == "") {
				document.getElementById('pan_number_error').innerHTML = 'Please enter pan card number.';
				document.getElementById("pan_number_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
				if (!reg.test(document.getElementById('pan_number_id').value.trim())) {
					document.getElementById('pan_number_error').innerHTML = 'Please enter valid pan card number.';
					document.getElementById("pan_number_class").classList.add("has-error");
					rt_type = false;
				}
				else{
					var pan_value = document.getElementById('pan_number_id').value.trim();
					
					if(getPancardUnique(pan_value))
					{
						
						document.getElementById('pan_number_error').innerHTML = 'Pan card number already exist.';
						document.getElementById("pan_number_class").classList.add("has-error");
						rt_type = false;
					}
				}
			}
			//for partner name
			if (document.getElementById('partner_id').value.trim() == "") {
				document.getElementById('partner_id_error').innerHTML = 'Please enter partner name.';
				document.getElementById("partner_id_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('partner_id').value.trim())) {
					document.getElementById('partner_id_error').innerHTML = 'Please enter valid partner name.';
					document.getElementById("partner_id_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for partner position
			if (document.getElementById('partner_position_id').value.trim() == "") {
				document.getElementById('partner_position_error').innerHTML = 'Please enter partner position.';
				document.getElementById("partner_position_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('partner_position_id').value.trim())) {
					document.getElementById('partner_position_error').innerHTML = 'Please enter valid partner position.';
					document.getElementById("partner_position_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for partner mobile number
			if (document.getElementById('partner_contact_id').value.trim() == "") {
				document.getElementById('partner_contact_error').innerHTML = 'Please enter partner contact number.';
				document.getElementById("partner_contact_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\d{10}$/;
				if (!reg.test(document.getElementById('partner_contact_id').value.trim())) {
					document.getElementById('partner_contact_error').innerHTML = 'Please enter valid partner contact number.';
					document.getElementById("partner_contact_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for partner email
			if (document.getElementById('partner_email_id').value.trim() == "") {
				document.getElementById('partner_email_error').innerHTML = 'Please enter partner email id.';
				document.getElementById("partner_email_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
				if (!reg.test(document.getElementById('partner_email_id').value.trim())) {
					document.getElementById('partner_email_error').innerHTML = 'Please enter valid partner email id.';
					document.getElementById("partner_email_class").classList.add("has-error");
					rt_type = false;
				}
			}
			
			//for staff name
			if (document.getElementById('staff_id').value.trim() == "") {
				document.getElementById('staff_id_error').innerHTML = 'Please enter staff name.';
				document.getElementById("staff_id_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('staff_id').value.trim())) {
					document.getElementById('staff_id_error').innerHTML = 'Please enter valid staff name.';
					document.getElementById("staff_id_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for staff position
			if (document.getElementById('staff_position_id').value.trim() == "") {
				document.getElementById('staff_position_error').innerHTML = 'Please enter staff position.';
				document.getElementById("staff_position_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^[a-zA-Z]+$/;
				if (!reg.test(document.getElementById('staff_position_id').value.trim())) {
					document.getElementById('staff_position_error').innerHTML = 'Please enter valid staff position.';
					document.getElementById("staff_position_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for staff mobile number
			if (document.getElementById('staff_contact_id').value.trim() == "") {
				document.getElementById('staff_contact_error').innerHTML = 'Please enter staff contact number.';
				document.getElementById("staff_contact_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\d{10}$/;
				if (!reg.test(document.getElementById('staff_contact_id').value.trim())) {
					document.getElementById('staff_contact_error').innerHTML = 'Please enter valid staff contact number.';
					document.getElementById("staff_contact_class").classList.add("has-error");
					rt_type = false;
				}
			}
			//for staff email
			if (document.getElementById('staff_email_id').value.trim() == "") {
				document.getElementById('staff_email_error').innerHTML = 'Please enter staff email id.';
				document.getElementById("staff_email_class").classList.add("has-error");
				rt_type = false;
			}
			else{
				var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
				if (!reg.test(document.getElementById('staff_email_id').value.trim())) {
					document.getElementById('staff_email_error').innerHTML = 'Please enter valid staff email id.';
					document.getElementById("staff_email_class").classList.add("has-error");
					rt_type = false;
				}
			}
			
			if(rt_type)
			{
				$('.form3').show();
				$('.form2').hide();
				$('.form1').hide();
			}	
		});
		$('#back2btn').click(function () {
			$('.form3').hide();
			$('.form2').show();
			$('.form1').hide();
		});
		$('#back1btn').click(function () {
			$('.form3').hide();
			$('.form2').hide();
			$('.form1').show();
		});
		
		/*$('input[name="pay_0"]').change(function(){
			if($('#banking').prop('checked')){
				$('.cardtype').hide();
				$('.banktype').show();
			}else{
				$('.cardtype').show();
				$('.banktype').hide();
			}
		  });*/
		  
		$(document).ready(function () {

			//show first tab and hide other all tabs
			$('.form1').show();
            $('.form2').hide();
            $('.form3').hide();
			//$('.save_card_failed').hide();

			var Num = 1
			var count = 0;

			$('#addbtn').on('click', function (event) {
				var rowNum = 0;
					rowNum = Num++;
					count = rowNum;

				$('.cardblockWrap').append(
				   
					'<div class="cardblock" data-row="' + rowNum +'"><div class="row"><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req" id="pay_class_'+rowNum+'"><div class="radio"><label class="radio-inline"><input id="pay_' + rowNum +'" class="radioBtn" name="pay_' + rowNum +'" type="radio" value="Credit Card" checked />Credit Card</label><label class="checkbox-inline"><input id="pay_' + rowNum +'" class="radioBtn" name="pay_' + rowNum +'" type="radio" value="Debit Card" /> Debit Card</label><label class="checkbox-inline"><input id="pay_' + rowNum +'" class="radioBtn" name="pay_' + rowNum +'" type="radio" value="Netbanking" /> Netbanking</label></div><div id="pay_error_'+rowNum+'" class="help-block"></div></div></div></div><div class="cardtype_'+rowNum+'"><div class="row"><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req" id="select_card_type_class_'+rowNum+'"><select id="select_card_type_' + rowNum +'" class="form-control" name="select_card_type_' + rowNum +'"><option value="">Select Card Type</option><option value="International">International</option><option value="Domestic">Domestic</option></select><div id="select_card_type_error_'+rowNum+'" class="help-block">&nbsp;</div></div></div><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req req"  id="card_number_class_'+rowNum+'"><input id="card_number_' + rowNum +'" class="form-control" name="card_number_' + rowNum +'" type="text" placeholder="Card Number" /><div id="card_number_error_'+rowNum+'" class="help-block">&nbsp;</div></div></div></div><div class="row"><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req req" id="card_nick_name_class_'+rowNum+'"><input id="card_nick_name_' + rowNum +'" class="form-control" name="card_nick_name_' + rowNum +'" type="text" placeholder="Card Nick Name" /><div id="card_nick_name_error_'+rowNum+'" class="help-block">&nbsp;</div></div></div><div class="col-sm-6 col-md-5 col-lg-4"><div class="row"><div class="col-sm-6"><div class="form-group req req" id="card_exp_month_class_'+rowNum+'"><select id="card_exp_month_' + rowNum +'" class="form-control" name="card_exp_month_' + rowNum +'"><option value="">Expiry Month</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select><div id="card_exp_month_error_'+rowNum+'" class="help-block">&nbsp;</div></div></div><div class="col-sm-6"><div class="form-group req req" id="card_exp_year_class_'+rowNum+'"><select id="card_exp_year_' + rowNum +'" class="form-control" name="card_exp_year_' + rowNum +'"><option value="">Expiry Year</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select><div id="card_exp_year_error_'+rowNum+'" class="help-block">&nbsp;</div></div></div></div></div></div><div class="row"><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req" id="cvv_class_' + rowNum +'"><input type="text" class="form-control" placeholder="CVV number" id="cvv_number_' + rowNum +'" name="cvv_number_' + rowNum +'"><div class="help-block" id="cvv_number_error_' + rowNum +'"></div></div></div></div><div class="form-group"><input class="btn btn-primary delete_btn" type="button" value="Delete Card" data-row="' + rowNum + '" /></div></div><div class="banktype_'+rowNum+'" style="display: none;"><div class="row"><div class="col-sm-6 col-md-5 col-lg-4"><div class="form-group req" id="select_bank_class_'+rowNum+'"><select id="select_bank_' + rowNum +'" class="form-control" name="select_bank_' + rowNum +'"><option value="">Select Bank</option><option value="">ICICI Bank</option><option value="">HDFC Bank</option><option value="">Axis Bank</option></select><div id="select_bank_error_'+rowNum +'" class="help-block">&nbsp;</div></div></div></div><div class="form-group req"><input class="btn btn-primary delete_btn" type="button" value="Delete Bank" data-row="' + rowNum + '" /></div></div>'
				);
				
				document.getElementById('save_card_count').value = count;
				
				
				bindRemoveBtnEvent();
				return false;
			});
			// add number of row in hidden field
			document.getElementById('save_card_count').value = count;
			
			bindRemoveBtnEvent();

			function bindRemoveBtnEvent() {

				$('.delete_btn').on('click', function (event) {
					var delBoxNum = $(event.currentTarget).attr('data-row');
					$('.cardblock[data-row="' + delBoxNum + '"]').remove();
				});
			}
			
			$('body').on('click', '.radioBtn', function () {
				var name = $(this).attr( "name" );
				var value = $(this).attr( "value" );
				var id = name.split("_");
				if(value == 'Netbanking')
				{
					//explicitly create select box 
					$.ajax({
					url: "/partnerpay/web/spicejet/agentregistration/getbankdata",  
					type: "POST",
					dataType: "json",
					success: function(data) {
						if(data['status'])
						{
							//console.log(data);
							var dhtml = "<option value=''>Select Bank</option>";
							$.each(data.result, function (ind, vl) {
						     
								dhtml += "<option value='" + vl.BANK_CODE + "'>" + vl.BANK_NAME + "</option>"
								
							});
							
							$("#select_bank_"+id[1]).html(dhtml);
							
						}
						
						}
					})
					
					//remove other validation
					//for select card type
					$("#select_card_type_error_" + id[1]).html('');
					$("#select_card_type_class_" + id[1]).removeClass("has-error");
				
					//for card exp month
					$("#card_exp_month_error_" + id[1]).html('');
					$("#card_exp_month_class_" + id[1]).removeClass("has-error");
				
					//for card exp year
					$("#card_exp_year_error_" + id[1]).html('');
					$("#card_exp_year_class_" + id[1]).removeClass("has-error");

					//for card nick name
					$("#card_nick_name_error_" + id[1]).html('');
					$("#card_nick_name_class_" + id[1]).removeClass("has-error");

					//for card number

					$("#card_number_error_" + id[1]).html('');
					$("#card_number_class_" + id[1]).removeClass("has-error");
					
					//for card CVV number

					$("#cvv_number_error_" + id[1]).html('');
					$("#cvv_class_" + id[1]).removeClass("has-error");
					
					$('.cardtype_'+id[1]).hide();
					$('.banktype_'+id[1]).show();
					
				}
				else
				{
					//remove other validation
					//for select bank 
					$("#select_bank_error_" + id[1]).html('');
					$("#select_bank_class_" + id[1]).removeClass("has-error");
					
					$('.cardtype_'+id[1]).show();
					$('.banktype_'+id[1]).hide();
				}
				
				
				
			}); 

		});
		function getPancardUnique(pan_card_value){
			var flag = false;
			$.ajax({
      		url: "/partnerpay/web/spicejet/agentregistration/getpannumber",  
      		data: {pan_id:pan_card_value},
     		type: "POST",
      		dataType: "json",
			async: false,
      		success: function(data) {
				console.log(data);
				if(data['status'] == '1')
				{
					
					flag = true;
										
				}
      		}
   		})
			return flag;
		}
		//function for card number validation
		function GetCardType(e) {
    		var t = new RegExp("^4");
    		if (e.match(t) != null) return "Visa~16";
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
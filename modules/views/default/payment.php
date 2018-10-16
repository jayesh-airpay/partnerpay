<link rel="stylesheet" href="/bbps/css/customs.css" type="text/css">
<div class="index">
	<div class="indexpage0">
	<div class="container">
<div class="loader"></div>
		<div class="logo" style="padding: 25px;">
			<div class="merlogo-head text-center">
                <img src="/bbps/images/bbps-logo.png" alt="bbpslogo">
            </div>

				<!--<div class="logoimg partnerpay"><img alt="Partnerpay" src="/bbps/images/partnerpay-logo.png"/></div>-->
				<!--<div class="logoimg banklogo"><img alt="mastercard" src="/bbps/images/mastercard.png"/></div>-->
			
		</div>
		<h3 class="text-center"><?=$provider;?></h3>
		
		<div class="row">
			<div class="col-md-12"> <div class="invoice-head"><b>Invoice No :</b> <?=$invoice_data[0]['INVOICE_ID'];?></div></div>
		</div>
		
		<div class="payformbox">
		
            <div class="row bg-msg">
			<div class="col-sm-12">
		    	<?php if(Yii::$app->session->hasFlash('error') || Yii::$app->session->hasFlash('success') ){?>
        			<div class="alert <?php if(Yii::$app->session->hasFlash('error')){echo 'alert-error';}else{echo 'alert-success';}?> alert-dismissable">
        				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        				<?php if(Yii::$app->session->hasFlash('error')){ echo Yii::$app->session->getFlash('error'); } else { echo Yii::$app->session->getFlash('success'); }?>
        			</div>
    			<?php }?>
			</div>
		</div>
		
       <form class="form" action="/bbps/default/pay" id="payment" method="post">
	   <div class="row">		
	   <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	   <input type="hidden" name="invoice_no" id="invoice_no" value="<?=$invoice_data[0]['INVOICE_ID'];?>" />
			<!-- <div class="col-sm-6">
				<div class="form-group req">
					<div class="form-group field-client-first_name required">
						<label class="control-label" for="merchant-id">Merchant Name</label>
						<input type="text" id="merchant_name" class="form-control" name="merchant">
						<div class="help-block"></div>
					</div>
				</div>
			</div> -->
		
		
			<div class="col-sm-6">
				<div class="form-group">
					<div class="form-group field-invoice-pay_amount">
						<label class="control-label" for="invoice-pay_amount">Amount</label>
						<input type="text" class="form-control" value= "<?php if(isset($invoice_amount)){
                           echo  $invoice_amount;
                        }?>" name="bill_amount" id="bill_amount" readonly="readonly">
						<div class="help-block"></div>
					</div>
				</div>
			</div>
		
			<div class="col-sm-6">
				<div class="form-group req">
					<div class="form-group field-invoice-pay_amount">
						<label class="control-label" for="invoice-pay_amount">PAYMENT MODE</label>
						<select class="form-control" name="payment_mode" id="payment_mode" onChange="applyCharge()">
						<option value="">SELECT PAYMENT MODE</option>
						<?php 
						 $modes = json_decode($charges['MODES'],true);?>
						<?php foreach($modes as $key=>$values) { ?>
							<option value="<?php echo  $key;?>"><?php if($values == 'Wallet'){echo $values." (Balance : Rs ".$wallet_balance.")";}else{echo $values;}?></option>
						<?php } ?>
						</select>
						<div class="help-block"></div>
					</div>
				</div>
			</div>
			</div>
		<div class="row">

			<div class="col-sm-6">
				<div class="form-group">
					<div class="form-group field-invoice-pay_amount">
						<label class="control-label" for="invoice-pay_amount">Total Amount</label>
						<input type="text" class="form-control" name="total_amount" id="total_amount" readonly="readonly" value="<?php if(isset($invoice_amount)){
                           echo  $invoice_amount;
                        } ?>">
						<input type="hidden" class="form-control" name="invoice_amount" id="invoice_amount" value="<?php if(isset($invoice_amount)){
                           echo  $invoice_amount;
                        } ?>">
						<div class="help-block"></div>
					</div>
				</div>
			</div>
		
			<div class="clearfix"></div>
			
			<div class="col-sm-12">
					<a href="#listing" class="" data-toggle="modal" data-target="#listing" >View Invoice listing</a></label>
			</div>
			
			<div class="col-sm-12 ">
				<div class="form-group field-invoice-iagree">
					<input type="checkbox" id="tandc1"  name="agree" value="1">
					<label>I accept the 
					<a href="#termandcond" class="tnclink required" data-toggle="modal" data-target="#termandcond">terms and conditions.</a></label>
					<div class="help-block"></div>
				</div>
			</div>

			<div class="col-sm-6 btngroup">
                <a href="#topup" id="top_up" class="btn btn-primary hidden" data-toggle="modal" data-target="#topup" >Topup</a>
				<button type="submit" onClick="loader()" id="pay" class="btn btn-primary">Pay</button>
				<a href="/bbps/default/listing"><button type="button" class="btn btn-primary">Cancel</button></a>
			</div>
			<form>
		</div>	
		</form>
	
				
		<div class="bott-logo">
            <div class="logoimg partnerpay"><img alt="Partnerpay" src="/bbps/images/partnerpay-logo.png"></div>
        	        	<!-- <div class="logoimg banklogo"><img alt="Bank Logo" src="/uploads/bank_logo/6ee4dc7e4c64897ea8fce3457a097edc.JPG"></div> -->
			</div>
		</div>
			

	</div>
	</div><!--/close .indexpage-->
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
			<table class="table table-striped table-bordered text-center" id="account_no_bill">
			<thead>
			<tr>
				<th class="text-center idnum">#</th>
				<th class="text-center">Account No.</th>
				<th class="text-center">Due Date</th>
				<th class="text-center">Amount</th>
				<th class="text-center action">&nbsp;</th>
			</tr>
			</thead>
			
			<tbody>
                <?php
                    $i=1;
                foreach($invoice_data as $invoice_value) {?>
					<tr id="<?=$invoice_value['ACCOUNT_NO'];?>">
						<td class="idnum" id="<?=$i;?>"><?=$i;?></td>
						<td><?=$invoice_value['ACCOUNT_NO'];?></td>
						<td><?=date("d-m-Y",strtotime($invoice_value['DUE_DATE']));?></td>
						<td class="text-right"><?=$invoice_value['AMOUNT'];?></td>
						<td class="action">
							<div class="bbox">
                        <?php if(sizeof($invoice_data)!=1){?>
								<a onClick="remove_mobile('<?=$invoice_value['ACCOUNT_NO'];?>','<?=$invoice_value['INVOICE_ID'];?>')"><span class="glyphicon glyphicon-trash"></span></a>
                        <?php }?>
							</div>
						</td>
					</tr>
                <?php $i++;} ?>
			</tbody>
			</table>
			</div>
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
        <div class="col-sm-12 walbal"><p>Wallet Balance : Rs <?php echo $wallet_balance; ?></p></div>
        <form class="form" action="/bbps/default/pay" id="wallet_topup" method="post">
            <div class="col-xs-6">                            
                <div class="form-group">
                <input type="text" class="form-control" id="amount" name="invoice_amount" placeholder="Enter Topup Amount" value="">
                    <input type="hidden" class="form-control" id="customvar" name="customvar" value="<?php echo 'BBPS|'.$_SERVER['REQUEST_URI'];?>">
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
</div>

        
<div class="modal fade" id="termandcond" tabindex="-1" role="dialog" aria-labelledby="listingLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Terms &amp; Condition </h4>
      </div>
      <div class="modal-body">
		<div class="tableboxpopup">	
		<p>By visiting or accessing Partnerpay payment Link (&#8220;Site&#8221;), the Customer agrees to be
              bound by the following Terms and Conditions and the
              Customers acceptance of the same is reaffirmed each time
              the Customer access the Payment Service (&#8220;Service&#8221;). The
              Site reserves the right, at its sole discretion, to
              change, modify, add, or delete portions of these Terms
              &amp; Conditions at any time without further notice. The
              Customer shall re-visit the&nbsp;<b>&#8220;Terms &amp; Conditions&#8221;</b>&nbsp;link
              from time to time to stay abreast of any changes that the
              Site may introduce.</p>
    
    <p>We do not collect or store
              Customer Credit Card or bank account information. In case
              of Credit Cards, you will be transferred to a secure
              payment gateway where you can enter your Credit Card
              details. The payment gateway authenticates the transaction
              and we would then initiate the booking request. For
              Internet Banking/ Debit Cards, you would be transferred to
              the relevant bank website where you are required to enter
              your Internet Banking login and password or your Debit
              Card details. The booking request is initiated once
              authentication is complete.<br>
    </p>
    <p>We shall not be liable for any
              failure of the payment gateway to authenticate the
              transaction or any failure of internet services during the
              transaction.<br>
              <br>
              The Customer agrees and undertakes not to sell, trade or
              resell or exploit for any commercial purposes, any portion
              of this Service. For the removal of doubt, it is clarified
              that this <br>
              Service is not for commercial use but is specifically
              meant for personal use only.<br>
              </p>
    <p>
              The Customer agrees and undertakes to abide by the
              provisions of Information Technology Act and/or any other
              laws/rules/regulations of India. The Customer impliedly
              and expressly undertakes to submit to the jurisdiction of
              enforcing/statutory authorities for any violation of the
              IT act and other laws.<br>
              <br>
              The Customer agrees and undertakes not to use the Service
              or the Site in any unlawful manner or in any other manner
              that could damage, disable, overburden, impair or disrupt
              the Service, servers, system, site or networks connected
              to the Service.</p>
    <p>
              As a condition of use of this Site, the Customer agrees to
              indemnify the Site and its affiliates from and against any
              and all actions, claims, losses, damages, liabilities and
              expenses (including reasonable attorneys' fees) arising
              out User's use of this Site.</p>
    <p>
              The Site is not to be held liable for special,
              consequential, incidental, indirect or punitive loss,
              damage or expenses , data, loss of facilities, or
              equipment or the cost of recreating lost data regardless
              of whether arising out of a breach of contract, warranty,
              tort, strict liability or otherwise.</p>
    <p>
              This Site contains links to third party sites. The linked
              sites are not under the control of the Site and the Site
              is not responsible for the contents of any linked site or
              any link contained in the linked site. The Site provides
              these links only as a convenience, and the inclusion of a
              link does not imply endorsement of the linked site by the
              Site.</p>
    <p>
            This Site and the contents hereof are provided "as is" and
            without warranties of any kind either expressed or implied
            to the fullest extent permissible pursuant to applicable
            law. The Site disclaims all warranties, express or implied,
            and conditions including, but not limited to, implied
            warranties of merchantability, fitness for particular
            purpose, title and non - infringement. Nor do we warrant
            that the functions of this Site or the functions contained
            in the materials of this Site will be interrupted or be
            error free, that defects will be corrected, or that this
            site or the server(s) that make the sites available are free
            of viruses or other harmful components. In no event shall we
            be liable for any special, indirect or consequential damages
            or any damages whatsoever resulting from loss of use, data
            or profits, whether in an action of contract, negligence or
            other wrongful actions, arising out of or in connection with
            the use or performance of any products, materials or
            information available from this Site. This Site in whole or
            in part, could include technical inaccuracies or
            typographical errors. Changes are periodically added to the
            information herein. Customer&#8217;s continued use of this Site
            following the posting of any change or modification of the
            Terms and Conditions will mean the Customer has accepted
            those changes or modifications.</p>
			</div>
		
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="/bbps/js/jquery.js"></script>  
<script>
function applyCharge(){
	var amount = $('#bill_amount').val();
	var charges  = <?php echo $charges['CHARGES']; ?>;  
	var charge_mode =  $('#payment_mode').val();
	taxRate = 0.18;
	var wallet = "<?php echo $wallet_balance;?>";
	if(charge_mode){
	calculatedAmount = (charges[charge_mode] * amount) / 100;
    b_chgs = calculatedAmount * taxRate;
    tot_amt = parseFloat(amount) + parseFloat(calculatedAmount) + parseFloat(b_chgs);
	$('#total_amount').val(parseFloat(tot_amt).toFixed(2));
	$('#invoice_amount').val(parseFloat(tot_amt).toFixed(2));
    	if(charge_mode == "ppc" && tot_amt>wallet){
			$('#pay').addClass('hidden');
			$('#top_up').removeClass('hidden');
		} else {
			$('#pay').removeClass('hidden');
			$('#top_up').addClass('hidden');
		}
	} else {
		$('#total_amount').val(amount);
		$('#invoice_amount').val(amount);
	}
}

function loader(){
	// $('.loader').show();
}
                        
 function remove_mobile(mobile_no,invoice_id){
    var csrf_token = "<?php echo Yii::$app->request->getCsrfToken()?>";
 	var wallet = "<?php echo $wallet_balance;?>";
    $.ajax({
					type: "POST",
      				url: "/bbps/default/deletemobile",
					data: {"invoice_id":invoice_id,"mobile_no":mobile_no,"_csrf":csrf_token},
					dataType:"json",
      				success: function(data) {
                            if(data){
								var charges  = <?php echo $charges['CHARGES']; ?>;
									var charge_mode =  $('#payment_mode').val();	  
								if(charge_mode){
									taxRate = 0.18;
									calculatedAmount = (charges[charge_mode] * data.sum) / 100;
    								b_chgs = calculatedAmount * taxRate;
    								tot_amt = parseFloat(data.sum) + parseFloat(calculatedAmount) + parseFloat(b_chgs);
								}else{
									tot_amt = data.sum;
								}
								$('#invoice_amount').val(parseFloat(tot_amt).toFixed(2));
								$('#total_amount').val(parseFloat(tot_amt).toFixed(2))
								$('#bill_amount').val(data.sum);
                            	var count = $('#'+mobile_no).find('.idnum').attr('id');
                                $('#'+mobile_no).remove();
								for(var i=parseInt(count)+1;i<=$('#account_no_bill tbody tr').length+1;i++){
									$('#'+i).empty().html(i-1);
									$('#'+i).attr('id',i-1);									
								}
								if($('#account_no_bill tbody tr').length == 1){
										$(".bbox").empty();
								}
								if(tot_amt<=wallet){
									$('#pay').removeClass('hidden');
									$('#top_up').addClass('hidden');
								}
                            }
	  					}
            		 });
 }
                     
 function walletTopUp(){
		$.ajax({
      url: "/partnerpay/web/bbps/default/wallet_top_up",  
      dataType: "json",
      success: function(data) {
          if(data.TRANSACTIONSTATUS == 200){
			  if($('#total_amount').val()<=data.WALLETBALANCE){
				$('#pay').removeClass('hidden');
				$('#top_up').addClass('hidden');
			  }else{
				alert ("Wallet is still less than wallet balance") ;
			  }
          } else {
              alert ("Error in Top Up Process please try again later")
          }
      }
   });
	}

     $(document).ready(function(){
		$('#payment_mode').val('');
		$('#total_amount').val($('#bill_amount').val());
	});
                        
	$(document).on('click','#top_up',function(){
    	$('#amount').val('');
	})                        
</script>
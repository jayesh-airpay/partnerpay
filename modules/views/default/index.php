<link rel="stylesheet" href="/bbps/css/customs.css" type="text/css">
<?php Yii::$app->getSession()->setFlash('page','biller')?>
<div class="wrapper00">
    <div class="container">
    
        <div class="page-header">    
			<?php if(Yii::$app->session->hasFlash('error') || Yii::$app->session->hasFlash('success') ){?>
        		<div class="alert <?php if(Yii::$app->session->hasFlash('error')){echo 'alert-error';}else{echo 'alert-success';}?> alert-dismissable">
        		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        		<?php if(Yii::$app->session->hasFlash('error')){ echo Yii::$app->session->getFlash('error'); } else { echo Yii::$app->session->getFlash('success'); }?>
        		</div>
    		<?php }?>
            <h4>Bharat Bill Payment System</h4>
            <div class="fieldstx closetab">
                    <a class="btn btn-default" href="javascript:void(0)">Back</a>
            </div>
        </div>
        
                            
                            
        
        <div class="row">
        
            <div class="col-xs-10 col-sm-5 col-md-4 wallet-wrap">
                <div class="wallet">
                    <div class="row">
                        <div class="col-xs-8">
                            Wallet balance: <span class="amount-tx" id="wallet_amount">Rs. <?php if($wallet_balance != ""){ echo $wallet_balance;} else {echo '0';}?></span>
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
                <div class="opcwrap">
                <ul class="thlist">
                    <?php 
                    foreach($utilities as $key=>$val) { 
                        ?>
                    <li>
                        <div class="opcbox">
                            <a href="javascript:void(0)" onClick='setUtility(<?php echo $val->utility_id;?>,"<?php echo $val->utility_name;?>")'>
                                <div class="<?php echo "timg i-".strtolower(substr($val->utility_name,0,3))?>"></div>
                                <h3 class="thh"><?php  echo $val->utility_name; ?></h3>
                            </a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            
                
                <form id="bill_details" action="/bbps/default/paying" method="post" enctype="multipart/form-data">
                    <div class="opcrow">
                    <div class="row">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <div class="col-md-4 opclist-warp">
                            <ul class="opclist" id="providers">
                            </ul>
                        </div>
                        <div class="col-md-8 opclist-box">
                            <h4>Bill Payment</h4>
                            <!--<div class="row">                           
                                <div class="col-sm-12 col-md-8 usertx">
                                    <label>Register this user</label>
                                    <div class="yesnoswitch">
                                    <input type="checkbox" name="register" class="yesnoswitch-checkbox" id="myonoffswitch5" checked="">
                                    <label class="yesnoswitch-label" for="myonoffswitch5">
                                        <span class="yesnoswitch-inner"></span>
                                        <span class="yesnoswitch-switch"></span>
                                    </label>
                                    </div>
                                </div>
                            </div>-->
                            <div><input type="hidden" id="utility_name" name="utility_name" value=""></div>
                            <div class="row" id="bulk">
                                <div class="col-sm-12 col-md-8">
                                    <div class="form-group">                    
                                        <div class="file">
                                            <input type="file" id="bulk_upload" title="Bulk Upload" name="bulk_upload">
                                        </div>   
                                        <span id="errbulk_upload"></span>              
                                    </div>
                                    <div id="download_csv">

                                    </div>
                                <p class="text-center">OR</p>
                                </div>
                            </div>      
                            <div class="row" id="single">
                                <div class="col-sm-12 col-md-8">                            
                                    <div class="form-group">
                                        <input type="text" class="form-control single" id="fname" name="fname" placeholder="Enter your First Name">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8">                            
                                    <div class="form-group">
                                        <input type="text" class="form-control single" id="lname" name="lname" placeholder="Enter your Last Name">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8">                            
                                    <div class="form-group">
                                        <input type="text" class="form-control single" id="email" name="email" placeholder="Enter your Email">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 mobile_number">                            
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-8">
                                    <div class="form-group">                    
                                    <!-- <input type="submit" class="btn btn-primary lg-btn" id="submitform" value="Submit"> -->
                                    <input type="submit" class="btn btn-primary lg-btn" value="Submit" name="submitButton">
                                    </div>
                                </div>
                            </div>
                        </div>          
                    </div>
                    </div>
                    </form>
                
                </div>
            </div>      
        </div>
      

        <script type="text/javascript" src="/bbps/js/jquery.js"></script>
        <script type="text/javascript" src="/bbps/js/bootstrap.file-input.js"></script>
        <script type="text/javascript" src="/bbps/js/customs.js"></script>


        
        
        
        
        
        
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
<script>
function setUtility(id,name){
    $("#utility_name").val(id);
    if($("#utility_name").val() != '2'){
        $('.mobile_number').empty();
        $('.mobile_number').html('<div class="form-group"><input type="text" class="form-control single" id="mobile_number" name="mobile_number" placeholder="Enter your Mobile Number"><div class="help-block"></div></div>');
    } else {
        $('.mobile_number').empty();
    }
     $.ajax({
      url: "/bbps/default/providers",  
      data: {utility_id: id},
      type: "POST",
      dataType: "json",
      success: function(data) {
            $('#providers').empty();
           $.each(data, function (key, value) {
                    var provider_list='<li><label class="radio-inline"><input type="radio" onClick="getFields()"';
                    if(key == 0 ){
                        provider_list = provider_list+'checked="checked"';
                    } 
                    provider_list = provider_list+'name="providers" id="providers" value="'+value.id+'">'+value.name+'</label></li>';
                    $('#providers').append(provider_list);
             });
             getFields();
      }
   });
}

function getFields(){
    var provider = $("input[name=providers]:checked").val();
	var utility = $("#utility_name").val();
    if(provider){
     $.ajax({
      url: "/bbps/default/get_fields",  
      data: {provider_id: provider},
      type: "POST",
      dataType: "json",
      success: function(data) {
            $('.dynamic').remove();
           $.each(data, function (key, value) {
                    var fields='<div class="col-sm-12 col-md-8 dynamic"><div class="form-group"><input type="text" name="'+value.field+'" class="dynamic_field form-control single" placeholder="Enter your '+value.field+'" value=""><span class="error"></span></div></div>';
                    $('#single').append(fields);
                 $('input[name="'+value.field+'"]').rules("add", { 
                    required:function(element){
                      return ($("#bulk_upload").val().length == 0);
                    },  
                    regex: new RegExp(value.validation),
                    messages: {
                        required: value.field+" is Required",
                        regex: 'Please Provide Proper '+value.field,
                    }
                });
            });
             $('#download_csv').empty().append("<a href='/bbps/default/download_csv_file?provider="+provider+"&utility="+utility+"' target='_blank' >Download Sample Format</a>")
             $('#bulk_upload').val('');
             $('#bulk_upload').removeAttr('disabled');
             $('.file-input-name').html('');
             $('.single').each(function(){
                 $(this).removeAttr('disabled');
                 $(this).val('');
            })
      }
   });
   }
}

$(document).on('change','#bulk_upload',function(){
    if($('#bulk_upload').val()!=""){
        $('.single').attr('disabled','true');
    }else{
        $('.single').removeAttr('disabled');
    }
});

$(document).on('change','.single',function(){
    $('.single').each(function(){
        console.log($(this).val());
        if($(this).val()!=''){
            $('#bulk_upload').attr('disabled','true');  
            return false;
        }else{
            $('#bulk_upload').removeAttr('disabled'); 
        }
    })
})

$(document).on('change','.single',function(){
    $('.single').each(function(){
        console.log($(this).val());
        if($(this).val()!=''){
            $('#bulk_upload').attr('disabled','true');  
            return false;
        }else{
            $('#bulk_upload').removeAttr('disabled'); 
        }
    })
})

function walletTopUp(){
    $.ajax({
      url: "/partnerpay/web/bbps/default/wallet_top_up",  
      dataType: "json",
      success: function(data) {
          if(data.TRANSACTIONSTATUS == 200){
              $('#wallet_amount').empty().html('Rs. '+data.WALLETBALANCE);
          } else {
              alert ("Error in Top Up Process please try again later")
          }
      }
   });
}

$(document).on('click','#add_topup',function(){
    $('#amount').val('');
})
</script>

<link rel="stylesheet" href="/bbps/css/customs.css" type="text/css">
<div class="wrapper">
    <div class="container">
        <div>
            <h2 style="text-align: center;">Thank you<br>Your data has been uploaded</h2>
            <div style="text-align: center;">
                Go to <a href="/bbps/default/listing"><b>listing</b></a> to pay the bill
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/bbps/js/jquery.js"></script>
    <script>
    $(document).ready(function(){
        var provider_id = "<?php echo $provider; ?>";
        var upload_error = '<?php echo $upload_error; ?>';
		var utility = '<?php echo $utility; ?>';
        if(provider_id && upload_error != 'null'){
            window.open('/bbps/default/download_csv_file?provider='+provider_id+'&utility='+utility+'&errors='+upload_error);
          }
    });
    </script>
</div>

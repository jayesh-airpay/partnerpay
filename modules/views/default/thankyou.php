<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="wrapper">
    <div class="container">
		<div class="merlogo-head text-center">
                <img src="/bbps/images/bbps-logo.png" alt="bbpslogo">
         </div>
		<?php if(isset($failed)){?>
        <div>
            <h2 style="text-align: center;"><?php echo $failed; ?></h2>
            <div class="redirect">
                <i class="fa fa-circle-o-notch fa-spin"></i>
                <p class="load"></i>Redirecting...</p>
            </div>
        </div>
        <?php } else { ?>
        <div>
            <h2 style="text-align: center;">Thank you<br>Your payment is done</h2>
        	<div class="redirect">
                <i class="fa fa-circle-o-notch fa-spin"></i>
                <p class="load"></i>Redirecting...</p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript" src="/bbps/js/jquery.js"></script>
<script>
    $(document).ready(function(){
        setInterval(function(){
            window.location.href = '/bbps/default/listing';
        },5000);
    });
</script>
$(document).ready(function() {
	
	
	$('input[type=file]').bootstrapFileInput();
	$('.file-inputs').bootstrapFileInput();
	$('#utSelect').prop('selectedIndex', -1);
	$('#utSelect2').prop('selectedIndex', -1);

	$('.gstrbtn').off('click').on('click', function (event) {
		$('.listbox').fadeOut(200);
		setTimeout(function () {
			$('.tablistbox').fadeIn(300);
			$('.closetab').fadeIn(300);
		}, 100);
	});
	
	$('.closetab').off('click').on('click', function () {
		$('.closetab').fadeOut(200);				
		$('.tablistbox').fadeOut(200);
		setTimeout(function () {
			$('.listbox').fadeIn(300);
		}, 100);
    });
	$('.start-gst-btn').on('click',function(){
		$('[role="presentation"]').removeClass('active');
		$('.tab-pane').removeClass('active');
		$('[href="#prepare"]').parents('li').addClass('active');
		$('#prepare').addClass('active');
	});
}); // (jQuery)End of use strict

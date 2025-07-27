var go;
jQuery(document).ready(function($) {
	var $ = jQuery;
	
	
	$('form').submit(function () {
		$('span.error').remove();
		go = true;
		$(this).find('.__required').each(function (i, el) {
	    	el = $(el);
	    	valid_required(el);
		});
		
		$(".numeric").each(function () {
			var el = $(this);
			var length = el.attr('length');
			if( el.val().length != length && el.val().length > 0 ){
				el.closest('tr').addClass('invalid');
				el.closest('td').append("<span class='error'>length must be "+length+"</span>");
				go = false;
			}
		});
		return go;
	});
	
	$('.__required').blur(function () {
		var el = $(this);
		if( $('#ui-datepicker-div').css('display') != 'block' || (!el.val() || el.val()==0 || el.val().length < 1 ) ){
			el.closest('td').find('span.error').remove();
			valid_required(el);
		}
	});
	
	$(".numeric").keyup(function () {
	    this.value = this.value.replace(/[^0-9\.]/g,'');
	});
});

// check if required is invalid
function valid_required (el) {
	if(!el.val() || el.val()==0 || el.val().length < 1 ){
		el.closest('tr').addClass('invalid');
		el.closest('td').append("<span class='error'>required</span>");
		go = false;
	} else {
		el.closest('tr').removeClass('invalid');
	}
}
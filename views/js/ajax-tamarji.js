jQuery(document).ready(function() {
	jQuery("select.department").change(function() {
		var dept_id = jQuery(this).val();
		jQuery.ajax({
			type : "post",
			url : myAjax.ajaxurl,
			data : {
				action : "getDoctorsByDepartment",
				dept_id : dept_id
			},
			success : function(response) {
				// if (response != 0) {
					jQuery("select.doctor").html(response);
				// }
			}
		});
	});
	jQuery("select.department").each(function(index) {
		if(jQuery("select.department").find('option').length == 2){
			var dept_id = jQuery(this).find('option').last().val();
			jQuery(this).val(dept_id);
			if(!jQuery("select.doctor option:selected") || jQuery("select.doctor option:selected").val() == 0){
				var dept_id = jQuery(this).val();
				jQuery.ajax({
					type : "post",
					url : myAjax.ajaxurl,
					data : {
						action : "getDoctorsByDepartment",
						dept_id : dept_id
					},
					success : function(response) {
						// if (response != 0) {
							jQuery("select.doctor").html(response);
						// }
					}
				});
			}
		}
	});
	
	var confirmed_word 	= $('span#confirmed_word').html();
	var cancelled_word 	= $('span#cancelled_word').html();
	var edit_word 		= $('span#edit_word').html();
	var delay_word 		= $('span#delay_word').html();
	
	//confirm reservation
	jQuery(".confirm_link").click(function(event){
		var this_confirm	= jQuery(this);
		var reserve_id 		= this_confirm.attr('data-id');	
		jQuery.ajax({
			type : "post",
			url : myAjax.ajaxurl,
			data : {
				action : "confirm_reservation",
				reserve_id : reserve_id
			},
			success : function(response) {				
				this_confirm.removeAttr("href").html('<span class="confirmed">'+confirmed_word+'</span>');
				this_confirm.closest('td').find('a[class="delay"]').html(delay_word);
				this_confirm.closest('tr').addClass('confirmed');
				// this_confirm.closest('td').find('span[class="confirmed-span"]').html(confirmed_word);
			}
		});
	});
	
	$('.confirmed').each(function (index) {
		$(this).closest('tr').addClass('confirmed');
	});
	
	//cancel reservation
	jQuery(".cancel_link").click(function(event){
		var this_cancel=jQuery(this);
		var reserve_id = this_cancel.attr('data-id');	
		jQuery.ajax({
			type : "post",
			url : myAjax.ajaxurl,
			data : {
				action : "cancel_reservation",
				reserve_id : reserve_id
			},
			success : function(response) {
				this_cancel.closest('tr').addClass('cancelled');
				this_cancel.closest('td').html(cancelled_word);
			}
		});
	});
	
	$('.cancelled').each(function (index) {
		$(this).closest('tr').addClass('cancelled');
	});
	
	// change rows limit
	jQuery(".autosubmit").on('change',function(event){
		var el			= jQuery(this);
		var limit 	    = jQuery(this).val();
		var meta 		= JSON.stringify( jQuery(this).closest('.table_group').find('input#meta').val() );
		var new_args 	= JSON.stringify( jQuery(this).closest('.table_group').find('input#new_args').val() );
		jQuery.ajax({
			type : "post",
			url : myAjax.ajaxurl,
			data : {
				action 	: "reservations_shortcut_change_limit",
				new_args : new_args,
				meta 	: meta,
				limit 	: limit
			},
			success : function(response) {
				el.closest('.table_group').html(response);
			}
		});
		  return false;
	});
});

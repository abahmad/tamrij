jQuery(document).ready(function($) {
	var $ = jQuery;
	$("#patient-tabs").tabs();
	$(".visitTabs").tabs();
//
jQuery( ".visit_details" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
    jQuery( ".visit" ).click(function() {
    	
    	//jQuery(".ui-dialog").css('width', '700px');
    	//alert(jQuery(".ui-dialog").width());
    	var visit_id=jQuery(this).attr('data-id');
       jQuery( ".visit"+ visit_id).dialog( "open" );
    });
	/* */

	//
	$(function() {
		$("tr[class=reservation_child]").hide();
		$("table.reservation").click(function(event) {
			var $target = $(event.target);
			var parent_id = $target.closest("tr").attr('data-id');
			$(".reservation_child[data-id=" + parent_id + "]").slideToggle();
			

		});
	});

	jQuery(function(jQuery) {
		jQuery(".nav-tab").click(function() {
			//alert(this);
			jQuery(".nav-tab").css('backgroundColor', '#f1f1f1');
			jQuery(this).css('backgroundColor', 'white');
		});
	});
	
});


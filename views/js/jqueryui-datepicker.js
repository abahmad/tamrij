jQuery(document).ready(function() {
	/////////// Table Expanded //////////////
	var $ = jQuery;
	$(function() {

		$('select#sort').change(function(event) {
			$("tbody").each(function(elem, index) {
				var arr = $.makeArray($("tr", this).detach());
				arr.reverse();
				$(this).append(arr);
				$("tbody tr").each(function() {
					var arrange = $(this).attr('class-id');

					$('.child' + arrange).insertAfter('.parent' + arrange);
				});
			});

		});
	});

	/////////// Date Picker //////////////
	$(function() {
		var d = new Date();
		var year = d.getFullYear();
		$("#Mnbaa_Tamarji_BirthDate").datepicker({
			// defaultDate : "+1w",
			changeMonth : true,
			maxDate: new Date,
			changeYear: true,
			yearRange: '1920:' + year,
			onClose : function(selectedDate) {
				$("#Mnbaa_Tamarji_BirthDate").datepicker("option", "dateFormat", 'yy-mm-dd');
			}
		});
		
	});

});


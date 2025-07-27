var $ = jQuery;
var count=1;
$(document).ready(function () {
	// $('.token-input-delete-token').on('click',function () {
		// $(this).closest('li').remove();
	// });
	
	$(".autoComplete").on('keypress blur', function(e) {
		var code = e.keyCode || e.which;
		if(code == 13 || e.type == 'blur') { //Enter keycode
		  	//Do something
		    var inputVal = $(this).val().trim();
		    if (inputVal.length > 1) {
			    var clone = $(this).closest('div#autoCompleteArea').find('.token-input-token-hidden').clone();
			    clone.find('input[type=hidden]').val(inputVal);
			    clone.find('p').html(inputVal);
			    // check if prescription case
			    if ($(this).attr('id') == 'prescription') {
			    	clone.find('input[type=text]').attr('name','post[visit][prescription]['+count+'][description]');
			    	clone.find('input[type=hidden]').attr('name','post[visit][prescription]['+count+'][name]');
			    };
				$(this).closest('div#autoCompleteArea').find('ul.token-input-list').append('<li class="token-input-token">'+clone.html()+'</li>');
				$(this).val('');
				count++;
			};
			return false;
		}
	});
	
	$(".autoComplete_analysis_report").on('keypress blur', function(e) {
		var code = e.keyCode || e.which;
		if(code == 13) {
			return false;
		}
	});
	
	function json_data () {
		jQuery.ajax({
			type : "post",
			url : myAjax.ajaxurl,
			data : {
				action : "get_visit_meta",
				//cat_id : cat_id
			},
			success : function(response) {
				// if (response != 0) {
					// jQuery("select[ajax-id=analysis_"+ajax_id+"]").html(response);
				// }
			}
		});
		//return availableTags;
	}

    function split( val ) {
      return val.split( /,\s*/ );
    }

    function extractLast( term ) {
      return split( term ).pop();
    }
    
    function isInArray(value, array) {
	  return array.indexOf(value) > -1;
	}
	
	function isArabic(text) {
	    var pattern = /[\u0600-\u06FF\u0750-\u077F]/;
	    result = pattern.test(text);
	    return result;
	}

	$('input.autoComplete').each(function(i, el) {
	    el = $(el);
	    el.autocomplete({
			source: function( request, response ) {
	          $.getJSON( myAjax.ajaxurl, {
	            action : "get_visit_meta",
	            key : el.attr('id'),
	            value: extractLast( request.term )
	          }, response );
	        },
	        search: function() {
	          // custom minLength
	          var term = extractLast( this.value );
	          if ( term.length < 0 ) {
	            return false;
	          }
	        },
	        focus: function() {
	          // prevent value inserted on focus
	          return false;
	        },
	        minLength: 1,//search after two characters
	        select: function( event, ui ) {
	          var terms = split( this.value );
	          // remove the current input
	          terms.pop();
	          // add the selected item
	          terms.push( ui.item.value );
	          // add placeholder to get the comma-and-space at the end
	          // terms.push( "" );
	          this.value = terms;
			
			// save data into li
	          var inputVal = $(this).val().trim();
			    if (inputVal.length > 1) {
				    var clone = $(this).closest('div#autoCompleteArea').find('.token-input-token-hidden').clone();
				    clone.find('input[type=hidden]').val(inputVal);
				    clone.find('p').html(inputVal);
				    // check if prescription case
				    if ($(this).attr('id') == 'prescription') {
				    	clone.find('input[type=text]').attr('name','post[visit][prescription]['+count+'][description]');
				    	clone.find('input[type=hidden]').attr('name','post[visit][prescription]['+count+'][name]');
				    };
					$(this).closest('div#autoCompleteArea').find('ul.token-input-list').append('<li class="token-input-token">'+clone.html()+'</li>');
					$(this).val('');
					count++;
				};
				return false;
	        }
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var str = '';
			var R = item.value.split(el.val());
			if(isArabic(el.val())){
				var L = ['ا','إ','أ','آ','د','ذ','ر','ز','و','ة'];
				var i;

				for (i = 0; i < R.length; ++i) {
					R_L = R[i];
					R_L = R_L.split('');
					
					str += R[i]; // +
					
					if(i==0 && R[i] !='' && !isInArray(R_L[R_L.length-1], L)){str +='ـ';} // + ?
					
					S_L = el.val().split('');
					if(i!=(R.length-1)){
						str += '<span class="autoCompleate-heighlited">'; // +
						
						str += el.val(); // +
						
						if(!isInArray(S_L[S_L.length-1], L)){str+='ـ';} // + ?
						
						str += '</span>'; // +
					
					}
					
					if( i!=(R.length-1) && R[i] !='' && !isInArray(S_L[S_L.length-1], L) ){str +='ـ';} // + ?
				}
			} else {
				// str += '<span class="autoCompleate-heighlited">'; // +
				// str += el.val(); // +
				// str += '</span>'; // +
				str += R.join('<span class="autoCompleate-heighlited">'+el.val()+'</span>');
			}
			
	      return $( "<li>" ).append( str ).appendTo( ul );
	    };
	});
	
	$('.check_hide').change(function() {
	    if(this.checked) {
	        $(this).closest('tr').find('#autoCompleteArea ul.token-input-list').addClass("hidden-ul");
	    }else{
	        $(this).closest('tr').find('#autoCompleteArea ul.token-input-list').removeClass("hidden-ul");
	    }
	});
	
	/////////////////// Analysis Report ////////////////////
	$('input.autoComplete_analysis_report').each(function(i, el) {
	    el = $(el);
	    el.autocomplete({
			source: function( request, response ) {
	          $.getJSON( myAjax.ajaxurl, {
	            action : "get_medical_analysis",
	            // key : el.attr('id'),
	            value: extractLast( request.term )
	          }, response );
	        },
	        search: function() {
	          // custom minLength
	          var term = extractLast( this.value );
	          if ( term.length < 0 ) {
	            return false;
	          }
	        },
	        focus: function() {
	          // prevent value inserted on focus
	          return false;
	        },
	        minLength: 1,//search after two characters
	        select: function( event, ui ) {
	          var terms = split( this.value );
	          // remove the current input
	          terms.pop();
	          // add the selected item
	          terms.push( ui.item.value );
	          // add placeholder to get the comma-and-space at the end
	          // terms.push( "" );
	          this.value = terms;
			
			// save data into li
	          var inputName = ui.item.name;
	          var inputID 	= ui.item.id;
			    if (inputName.length > 1) {
				    var clone = $(this).closest('div#autoCompleteArea').find('.token-input-token-hidden').clone();
				    clone.find('input.analysis_id').val(inputID);
				    clone.find('p').html(inputName);
				    // check if prescription case
				    // if ($(this).attr('id') == 'prescription') {
				    	clone.find('input.analysis_id').attr('name','post[analysis_report]['+count+'][analysis_id]');
				    	clone.find('input.value').attr('name','post[analysis_report]['+count+'][value]');
				    	clone.find('input.patient_id').attr('name','post[analysis_report]['+count+'][patient_id]');
				    // };
					$(this).closest('div#autoCompleteArea').find('ul.token-input-list').append('<li class="token-input-token">'+clone.html()+'</li>');
					$(this).val('');
					count++;
				};
				return false;
	        }
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var str = '';
			var R = item.name.split(el.val());
			if(isArabic(el.val())){
				var L = ['ا','إ','أ','آ','د','ذ','ر','ز','و','ة'];
				var i;

				for (i = 0; i < R.length; ++i) {
					R_L = R[i];
					R_L = R_L.split('');
					
					str += R[i]; // +
					
					if(i==0 && R[i] !='' && !isInArray(R_L[R_L.length-1], L)){str +='ـ';} // + ?
					
					S_L = el.val().split('');
					if(i!=(R.length-1)){
						str += '<span class="autoCompleate-heighlited">'; // +
						
						str += el.val(); // +
						
						if(!isInArray(S_L[S_L.length-1], L)){str+='ـ';} // + ?
						
						str += '</span>'; // +
					
					}
					
					if( i!=(R.length-1) && R[i] !='' && !isInArray(S_L[S_L.length-1], L) ){str +='ـ';} // + ?
				}
			} else {
				// str += '<span class="autoCompleate-heighlited">'; // +
				// str += el.val(); // +
				// str += '</span>'; // +
				str += R.join('<span class="autoCompleate-heighlited">'+el.val()+'</span>');
			}
			
	      return $( "<li>" ).append( str ).appendTo( ul );
	    };
	});
	
	
	// $(".autoComplete").blur(function(e) {
	    // var inputVal = $(this).val().trim();
		// if (inputVal.length > 1) {
			    // var clone = $(this).closest('div#autoCompleteArea').find('.token-input-token-hidden').clone();
			    // clone.find('input[type=hidden]').val(inputVal);
			    // clone.find('p').html(inputVal);
			    // // check if prescription case
			    // if ($(this).attr('id') == 'prescription') {
			    	// clone.find('input[type=text]').attr('name','post[prescription]['+count+'][description]');
			    	// clone.find('input[type=hidden]').attr('name','post[prescription]['+count+'][name]');
			    // };
				// $(this).closest('div#autoCompleteArea').find('ul.token-input-list').append('<li class="token-input-token">'+clone.html()+'</li>');
				// $(this).val('');
				// count++;
			// };
		// return false;
	// });
	
	$('body').on('click','.token-input-delete-token',function () {
		$(this).closest('li').remove();
		// if($(".token-input-list").find('li').size()==1){
			// $(".autoComplete").attr("name", "mnbaa_seo_keywords[]");
		// }
	});
});

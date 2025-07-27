<?php
// get Doctors By Department
function getDoctorsByDepartment() {
	$args  = array(
	    'role'      => 'Mnbaa_Tamarji_Doctor_Role',
	    'orderby'   => 'ID',
	    'fields'    => 'all',
		'meta_query'     => array(
			array(
				'key'       => 'department_id',
				'value'     => $_POST['dept_id'],
				'compare'   => '=',
				'type'      => 'NUMERIC',
			),
		),
	);
	
	// Create the WP_User_Query object
	$wp_user_query = new WP_User_Query($args);

	// Get the results
	$doctors = $wp_user_query->get_results();
	// var_dump($doctors);
	// check to see if we have users
	if (!empty($doctors)) {
		if (count($doctors>1)) {?>
		<option value="0"><?php echo __('Doctors','tamarji') ?></option>
		<?php
		}
		 // loop trough each author
	    foreach ($doctors as $doctor) {
	        $doctor_info = get_userdata($doctor->ID);
			?>
	<option value="<?php echo $doctor_info->ID?>"><?php echo $doctor_info->display_name?></option>
			<?php
		}
	}
	die();
}

// autoComplete Visit form
function get_visit_meta(){
	global $wpdb;
	$key = $_GET['key'];
	$val = $_GET['value'];
	$query = $wpdb->get_results("
	  SELECT distinct(meta_value)
	  FROM $wpdb->postmeta as postmeta
	  WHERE postmeta.meta_key = '$key'
	  AND postmeta.meta_value LIKE '%$val%'
	  GROUP BY postmeta.meta_value
	");
	$x = array();
	foreach ($query as $k => $value){
		if($value->meta_value != ''){
			if($key != 'prescription')
				$x[] = $value->meta_value;
			else{
				$prescription_r = unserialize($value->meta_value);
				$x[] = $prescription_r['name'];
			}
		}
	}
	$x = array_unique($x);
  	echo json_encode($x);
	die();
}

// autoComplete Visit form
function get_medical_analysis(){
	global $wpdb;
	// $key = $_GET['key'];
	$val = $_GET['value'];
	$args = array(
	    'meta_query' => array(
	        array(
	            'key' 		=> 'analysis_name',
	            'value' 	=> $val,
	            'compare' 	=> 'LIKE'
	        )
	    ),
	    'post_type' => 'analysis',
	    'posts_per_page' => 10
	);
	$analysiss = get_posts($args);
	$x = array();
	foreach ($analysiss as $k => $analysis){
		if($analysis->analysis_name != ''){
			$x[$k]['id'] 	= $analysis->ID;
			$x[$k]['name'] 	= $analysis->analysis_name;
		}
	}
	// $x = array_unique($x);
  	echo json_encode($x);
	die();
}

// confirim reservation
function confirm_reservation(){
	$date = new DateTime();
	$now = $date -> format('Y-m-d');
	$post_id=$_POST['reserve_id'];
	update_post_meta($post_id,'confirmedOn',$now);
	die();
}
// cancel reservation
function cancel_reservation(){
	$date = new DateTime();
	$now = $date -> format('Y-m-d');
	$post_id=$_POST['reserve_id'];
	update_post_meta($post_id,'cancelledOn',$now);
	die();
}

function reservations_shortcut_change_limit() {
	$limit 		= $_POST['limit'];
	
	$meta_query =  substr(str_replace('\\', '', $_POST['meta']), 1, -1);
	$meta_query = json_decode($meta_query);
	
	$new_args =  substr(str_replace('\\', '', $_POST['new_args']), 1, -1);
	$new_args 	= json_decode($new_args);
	
	$meta = array();
	foreach ($meta_query as $obj) {
		$meta[] = objectToArray($obj);
	}
	
	// $new = array();
	$new = (array) $new_args;
	// foreach ($new_args as $obj) {
		// $new[] = objectToArray($obj);
	// }
	
	$new['posts_per_page'] = $limit;
	
	reservations_shortcut($meta, $new);
	?>
	<script type="text/javascript" charset="utf-8">
		jQuery(".autosubmit").on('change',function(event){
		var el			= jQuery(this);
		var limit 	= jQuery(this).val();
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
	</script>
	<?php
	die();
}
?>
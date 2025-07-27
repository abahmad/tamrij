<!-- -------------------- Doctor Reservations --------------------------- -->
<h2><?php _e('Doctor Dashboard','tamarji');?></h2>
<?php
//get day of now
$date = new DateTime();
$now = $date -> format('Y-m-d');

$doctor_id = get_current_user_id();
$department_id = get_user_meta($doctor_id, 'department_id', 1);

global $wpdb;

$noVisits = $wpdb->get_results( "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key = 'reservation_id' " );

$ids = array();
foreach ($noVisits as $row) {
	$ids[] = $row->meta_value;
}?>


<br class="clear" />
<h3><?php _e('My Reservations For Today','tamarji');?></h3>
<?php 
$meta_query = array(
		// array(
		    // 'key' => 'attendOn',
		    // 'value' => $now,
			// ),
		array(
		    'key' => 'confirmedOn',
		    'value' => '',
		    'compare' => '!=',
			),
		array(
		    'key' => 'doctor_id',
		    'value' => $doctor_id,
			),
		array(
		    'key' 		=> 'cancelledOn',
		    'value' 	=> '',
		    'compare' => 'IN',
			)
	);
$new_args = array(
		'posts_per_page' => 10,
		'post__not_in' => $ids
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>
<br class="clear" />

<!-- -------------------- Department Reservations --------------------------- -->
<h3><?php _e('Department Reservations','tamarji');?></h3>
<?php 
$meta_query = array(
		// array(
		    // 'key' => 'attendOn',
		    // 'value' => $now,
			// ),
		array(
		    'key' => 'confirmedOn',
		    'value' => '',
		    'compare' => '!=',
			),
		array(
		    'key' => 'department_id',
		    'value' => $department_id,
			),
		array(
		    'key' 		=> 'doctor_id',
		    'value' 	=> '0',
			),
		array(
		    'key' 		=> 'cancelledOn',
		    'value' 	=> '',
			)
	); 
$new_args = array(
		'posts_per_page' => 10,
		'post__not_in' => $ids
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>
<br class="clear" />

<!-- -------------------- Visited Reservations --------------------------- -->
<h3><?php _e('Resent Visits','tamarji');?></h3>
<?php

$meta_query = array(
		array(
		    'key'		=> 'doctor_id',
		    'value' 	=> $doctor_id,
			),
		array(
		    'key' 		=> 'cancelledOn',
		    'value' 	=> '',
			)
	);
$new_args = array(
		'posts_per_page' => 10,
		'post__in' => $ids
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>
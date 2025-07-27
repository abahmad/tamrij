<h2><?php _e('Assistant Dashboard','tamarji');?></h2>
<?php
//get day of now
$date = new DateTime();
$now = $date -> format('Y-m-d')?>

<br class="clear" />
<h3><?php _e('Confirmed Reservations','tamarji');?></h3>
<?php
global $wpdb;

$noVisits = $wpdb->get_results( "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key = 'reservation_id' " );

$ids = array();
foreach ($noVisits as $row) {
	$ids[] = $row->meta_value;
}

// confirmed , !cancelled
$meta_query = array(
		array(
		    'key' => 'confirmedOn',
		    'value' => '',
	    	'compare' 	=> '!=',
			),
		array(
		    'key' 		=> 'cancelledOn',
		    'value' 	=> '',
			)
	);
$new_args = array(
		'posts_per_page' 	=> 10,
		'post__not_in'		=> $ids
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>

<!-- ----------- -->
<br class="clear" />
<h3><?php _e('Not Confirmed','tamarji');?></h3>
<?php 
$meta_query = array(
		array(
		    'key' => 'confirmedOn',
		    'value' => '',
			),
		array(
		    'key' 		=> 'cancelledOn',
		    'value' 	=> '',
			)
	);
$new_args = array(
		'posts_per_page' => 10
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>

<br class="clear" />
<h3><?php _e('Cancelled','tamarji');?></h3>
<?php 
$meta_query = array(
	// array(
	    // 'key' => 'attendOn',
	    // 'value' => $now,
		// ),
	array(
	    'key' 		=> 'cancelledOn',
	    'value' 	=> '',
	    'compare' 	=> '!=',
		)
	);
$new_args = array(
		'posts_per_page' => 10
	);
?>
<div class="table_group">
<?php reservations_shortcut($meta_query, $new_args) ?>
</div>
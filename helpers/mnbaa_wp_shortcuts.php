<?php
// get reservations list table by $args
function reservations_shortcut($meta_query, $new_args) {
	$limit = $new_args['posts_per_page'];
?>
	<select class="autosubmit">
		<option value="10" <?php if($limit == 10) echo "selected"?>>10</option>
		<option value="15" <?php if($limit == 15) echo "selected"?>>15</option>
		<option value="20" <?php if($limit == 20) echo "selected"?>>20</option>
		<option value="25" <?php if($limit == 25) echo "selected"?>>25</option>
		<option value="50" <?php if($limit == 50) echo "selected"?>>50</option>
		<option value="100" <?php if($limit == 100) echo "selected"?>>100</option>
	</select>
	<input type="hidden" id="meta" value='<?php echo json_encode($meta_query)?>' />
	<input type="hidden" id="new_args"  value='<?php echo json_encode($new_args)?>' />
	<table class="widefat">
		<thead>
			<tr>
				<th><?php echo __('Department', 'tamarji'); ?></th>
				<th><?php echo __('Doctor', 'tamarji'); ?></th>
				<th><?php echo __('Patient', 'tamarji'); ?></th>
				<th><?php echo __('Attend Date', 'tamarji'); ?></th>
				<th><?php echo __('Reserved Date', 'tamarji'); ?></th>
				<th><?php echo __('Complaint', 'tamarji'); ?></th>
				<?php if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_visits')): ?>
				<th><?php echo __('Visit', 'tamarji'); ?></th>
				<?php endif ?>
				<th><?php echo __('Actions', 'tamarji'); ?></th>
			</tr>
		</thead>
	<?php
	$basic_args = array(
		'post_type' 		=> 'reservation',
		'post_status'		=> 'publish',
		'meta_query' 		=> $meta_query
	);
	$args = array_merge($basic_args, $new_args);
	// echo "<pre style='direction: ltr'>";
	// print_r($args);
	// echo "</pre>";
	$query = new WP_Query($args);
	// echo "<hr>";
	// echo $query->request;
	// echo "<hr>";
	if($query -> have_posts()){
		while ($query -> have_posts()){
			$query -> the_post();
			$reservation_id	= $query -> post -> ID;
			$reservation 	= get_post($reservation_id);
			$department		= get_term($reservation->department_id, 'Department');
			$doctor			= get_userdata($reservation->doctor_id);
			$patient		= get_userdata($reservation->patient_id);
			$lastModified 	= new DateTime($reservation->lastModified);
			
			$confirmed = ($reservation->confirmedOn) ? 1 : 0;
			$cancelled = ($reservation->cancelledOn) ? 1 : 0;
			
			$tr_class 	= ($cancelled) ? 'cancelled' : '';
			$tr 		= ($confirmed && !$cancelled) ? 'confirmed' : '';
			?>
			<tr class="<?php echo $tr_class . ' ' . $tr?>">
				<td ><?php echo $department->name ;?></td>
				<td ><?php echo ($reservation->doctor_id) ? $doctor->display_name : __('None', 'tamarji')?></td>
				<td >
					<?php
					// hidden words in current language
					echo "<span id='confirmed_word' style='display:none'>" . __('Confirmed' , 'tamarji') . "</span>";
					echo "<span id='cancelled_word' style='display:none'>" . __('Cancelled' , 'tamarji') . "</span>";
					echo "<span id='edit_word' style='display:none'>" . __('Edit' , 'tamarji') . "</span>";
					echo "<span id='delay_word' style='display:none'>" . __('Delay' , 'tamarji') . "</span>";
					
					if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_view_patients_files'))
						echo "<a href='admin.php?page=patient_file&patient_id=$reservation->patient_id'>".$patient->display_name."</a>";
					else
						echo $patient->display_name;?></td>
				<td ><?php echo $reservation->attendOn; ?></td>
				<td ><?php echo $reservation->confirmedOn ?></td>
				<td ><?php echo substr($reservation->complaint, 0, 50) ?></td>
				
				<?php if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_visits')): ?>
				<td >
					<?php
					$args = array(
						'post_type' => 'visit',
						'post_status'=>'publish',
				    	'meta_query' => array(
					        array(
					            'key' => 'reservation_id',
					            'value' => $reservation_id
					        	)
				    		)
						);
					$sub_query = new WP_Query( $args );
					if ( $sub_query->have_posts() ){
						
						$sub_query -> the_post();
						$post_id = $sub_query -> post -> ID;
						echo "<a href='post.php?post=$post_id&action=edit&reservation_id=$reservation_id'>".__('Visit','tamarji')."</a>";
					}
					else echo "<a href='post-new.php?post_type=visit&reservation_id=$reservation_id'>".__('New Visit','tamarji')."</a>";
					?>
				</td>
				<?php endif?>
				
				<td >
					<?php
					if ($cancelled) {?>
						<span class='cancelled'><?php _e('cancelled','tamarji')?></span>
					<?php
					} else {
						echo "<a href='javascript:void(0);' data-id='{$reservation_id}' class='cancel_link'>".__('Cancel','tamarji')."</a> ";
						
						$confirmed_word = ($confirmed)? __('Delay','tamarji') : __('Edit','tamarji');
						echo "<a href='post.php?post={$reservation_id}&action=edit' class='delay'>" . $confirmed_word . "</a> ";
						
						if ($confirmed) {
							echo "<span class='confirmed'>".__('Confirmed','tamarji')."</span> ";
						} else {
							if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_confirm_reservation')){
								echo "<a href='javascript:void(0);' data-id={$reservation_id} class='confirm_link '>".__('Confirm','tamarji')."</a> ";
							}
						}
					}
					
					?>
				</td>
			</tr>
			
			<?php
		}
	}else{?>
		<tr class="<?php echo $tr_class . ' ' . $tr?>">
			<td><?php echo __("No Reservations Found",'tamarj'); ?></td>
		</tr>
	<?php }?>
	</table>
<?php
}?>
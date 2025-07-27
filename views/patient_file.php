<div id="patient-tabs">
	<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
		<ul class="category-tabs" style="margin:0px;padding:0px;">
			<li>
				<a href="#tabs-1" ><?php echo __('Patient', 'tamarji'); ?></a>
			</li>
			<li>
				<a href="#tabs-2"><?php echo __('Reservastions', 'tamarji'); ?></a>
			</li>
			<li>
				<a href="#tabs-3"><?php echo __('Medical Analysis', 'tamarji'); ?></a>
			</li>
		</ul>
	</h2>
	<div id="tabs-1">
		<?php
		$patient_id = (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_view_patients_files')) ? $_GET['patient_id'] : get_current_user_id();
		//$patient_id=get_post_meta($reservation_id,'patient_id','true');
		$patient = get_userdata($patient_id);
		?>
		<table class="form-table  patient">
			<tr><th><?php echo __('Username', 'tamarji'); ?></th><td><?php echo $patient -> user_login; ?></td></tr>
			<tr><th><?php echo __('Full Name', 'tamarji'); ?></th><td><?php echo $patient -> display_name; ?></td></tr>
			<tr><th><?php echo __('Birth Date', 'tamarji'); ?></th><td><?php echo $patient -> birthDate; ?></td></tr>
			<tr><th><?php echo __('Gender', 'tamarji'); ?></th><td><?php
			if ($patient -> gender == 0)
				echo "male";
			else
				echo "female";
	 		?></td>
			<tr><th><?php echo __('Natonal Id', 'tamarji'); ?></th> <td><?php echo $patient -> national_id; ?></td></tr>
			<tr><th><?php echo __('Email', 'tamarji'); ?></th><td><?php echo $patient -> user_email; ?></td></tr>
		</table>


	</div><!--end of tab1----->
	
	<div id="tabs-2">
		<table class="reservation widefat" cellspacing="0" cellpadding="0">
		<?php
		$args = array('post_type' => 'reservation', 'meta_query' => array( array('key' => 'patient_id', 'value' => $patient_id)));
		$query = new WP_Query($args);
		$date = new DateTime();
		$now = $date -> format('Y-m-d');
		?>
  		<tr data-id="<?php echo $item->id?>" class="reservations_th">
			<th><p><?php echo __('Reserve Date', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Attend Date', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Confirmation Date', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Cancel Date', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Department', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Doctor', 'tamarji'); ?></p></th>
			<th><p><?php echo __('Visit', 'tamarji'); ?></p></th>
		</tr>
		<?php 
		while ($query -> have_posts()) {
			
			$reservations=$query -> the_post();
			$reservation_id=$query -> post -> ID;
			$lastModified = get_post_meta($query -> post -> ID, 'lastModified', true);
			$lastModified = new DateTime($lastModified);
			$lastModified=date_format($lastModified,'Y-m-d');
			$attendOn = get_post_meta($query -> post -> ID, 'attendOn', true);
			// $attendOn = $reservations->attendOn;
			$confirmedOn = get_post_meta($query -> post -> ID, 'confirmedOn', true);
			$cancelledOn = get_post_meta($query -> post -> ID, 'cancelledOn', true);
			$department_id=get_post_meta($query -> post -> ID, 'department_id', true);
			$department=get_term($department_id, 'Department');
			$doctor_id=get_post_meta($query -> post -> ID, 'doctor_id', true);
			$doctor=get_userdata($doctor_id);
	 ?>
		
		
		<tr class="parent <?php echo($attendOn <= $now) ? "soon" : "done"; ?>" data-id=<?php echo $reservation_id ?> >
		<td ><?php echo $lastModified; ?></td>
		<td ><?php echo $attendOn; ?></td>
		<td ><?php echo $confirmedOn ?></td>
		<td ><?php echo $cancelledOn ?></td>
		<td ><?php echo $department->name?></td>
		<td ><?php echo $doctor -> display_name; ?></td>
		
			
		<?php 
		$visit_args = array(
				'post_type' => 'visit',
				'post_status'=>'publish',
		    	'meta_query' => array(
		        array(
		            'key' => 'reservation_id',
		            'value' => $reservation_id
		        	)
	    		)
			);
			$visit_query = new WP_Query( $visit_args );
			if ( $visit_query->have_posts() ){
				
				$visit_query -> the_post();
				$visit_id=$visit_query -> post -> ID;
				?>
				<td>
					<a class="visit" href="javascript:void(0);" data-id="<?php echo $visit_id?>">
						<?php _e('Visit','tamarji')?>
					</a>
					<div class="visit_details visit<?php echo $visit_id; ?>">
						<div class="visitTabs ">
							<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
								<ul class="category-tabs popup-tabs" style="margin:0px;padding:0px;">
									<li>
										<a href="#tabs-3" ><?php echo __('Symptoms', 'tamarji'); ?></a>
									</li>
									<li>
										<a href="#tabs-4"><?php echo __('Complaint', 'tamarji'); ?></a>
									</li>
									<li>
										<a href="#tabs-5" ><?php echo __('Diagnosis', 'tamarji'); ?></a>
									</li>
									<li>
										<a href="#tabs-6" ><?php echo __('Prescription', 'tamarji'); ?></a>
									</li>
								</ul>
							</h2>
						<div id="tabs-3">
							<div id="autoCompleteArea">
								<ul class="token-input-list">
									<?php foreach (get_post_meta($visit_id, 'symptom') as $meta_value): ?>
									<li class="token-input-token">
										<p><?php echo $meta_value?></p>
									</li>
									<?php endforeach ?>
								</ul>
					        </div>
						</div>
						<div id="tabs-4">
							<div id="autoCompleteArea">
								<ul class="token-input-list">
									
									<?php foreach (get_post_meta($visit_id, 'complaint') as $meta_value): ?>
									<li class="token-input-token">
										<p><?php echo $meta_value?></p>
									</li>
									<?php endforeach ?>
								</ul>
					        </div>
					        
							
						</div>
						<div id="tabs-5">
							<div id="autoCompleteArea">
								<ul class="token-input-list">
									
									<?php foreach (get_post_meta($visit_id, 'diagnosis') as $meta_value): ?>
									<li class="token-input-token">
										<p><?php echo $meta_value?></p>
									</li>
									<?php endforeach ?>
								</ul>
					        </div>
						</div>
						<div id="tabs-6">
							<div id="autoCompleteArea">
								<ul class="token-input-list">
									
									<?php foreach (get_post_meta($visit_id, 'prescription') as $k => $meta_value):
										$meta_value = unserialize($meta_value) ?>
									<li class="token-input-token">
										<p><?php echo $meta_value['name'] ?></p>
										<p class="desc"><?php echo $meta_value['description'] ?></p>
									</li>
									<?php endforeach ?>
								</ul>
			          		</div>
						</div>
					</div>
				</div>
			</td>
				
			<?php
				//echo "<a href='post.php?post=$post_id&action=edit&reservation_id=$id'>".__('Visit','tamergy')."</a>";
			}
			else echo "<td>".__('No Visits','tamaraji')."</td>";
			?>
		</tr>
		
		
	<?php
	
	$history=get_post_meta($query -> post -> ID,'history',false);
	if($history[0]){
	foreach ($history as $key=>$history_item) {
		//var_dump($history_item);
		$history_item = (object) $history_item;
		
		$department=get_term($history_item->department_id, 'Department');
		$doctor=get_userdata($history_item->doctor_id);
		?>
			
		<tr class="reservation_child" data-id="<?php echo $reservation_id?>" >
			<td ><?php echo $history_item->lastModified; ?></td>
			<td ><?php echo $history_item->attendOn; ?></td>
			<td ><?php echo $history_item->cancelledOn ?></td>
			<td ><?php echo $department->name?></td>
			<td ><?php echo $doctor -> display_name; ?></td>
			<td ><?php echo $history_item -> notes; ?></td>
			<td></td>
		</tr>
	
	<?php }
		}		
	} //end  for while
	?>
		</table>
	</div><!--end of tab2----->
	
	<div id="tabs-3">
		<?php
		global $wpdb;
		$sql = "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key = 'date' AND post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type='analysis_report') ";
		$report_dates = $wpdb->get_results( $sql );
		// echo $dates[0]->meta_value;
		
		foreach ($report_dates as $k => $report_date) {
		
			$args = array(
				'post_type' 		=> 'analysis_report',
				'post_status'		=> 'publish',
				'meta_query' 		=> array(
						array(
						    'key' => 'patient_id',
						    'value' => $patient_id,
							),
						array(
						    'key' => 'date',
						    'value' => $report_date->meta_value,
							)
					)
			);
			
			$query = new WP_Query($args);
		?>
		<h2><?php echo $report_date->meta_value; ?></h2>
		<table border="0" cellspacing="5" cellpadding="5">
			<tr>
				<th>analysis</th>
				<th>value</th>
			</tr>
			<?php
			if($query -> have_posts()){
				while ($query -> have_posts()){ $query -> the_post();
					$analysis = get_post($query->post->analysis_id);
				?>
					<tr>
						<td><?php echo $analysis->analysis_name ?></td>
						<td><?php echo $query->post->value ?></td>
					</tr>
				<?php
				}
			}
			?>
		</table>
<?php 		
		} ?>
	</div><!--end of tab3----->
</div>

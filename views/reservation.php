<?php
$reservation 		= get_post();
$reservation_type	= get_post($reservation->type);
global $blog_id;
?>
	<table class="form-table">
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Type','tamarji'), "post[reservation][type]") ?>
			</th>
			<td>
				<select name="post[reservation][type]" class="__required">
					<option value="0"><?php echo __('Types','tamarji') ?></option>
					<?php
					$args = array(
						'post_type' => 'reservation_type',
					);
					$query = new WP_Query( $args );

					// Get the results
					$types = $query->get_posts();
					
					// check to see if we have users
					if (!empty($types)) {
						 // loop trough each author
					    foreach ($types as $type) {?>
					<option value="<?php echo $type->ID?>" <?php echo($reservation->type==$type->ID)?'selected':''?>><?php echo $type->type_name?></option>
					<?php }
					}?>
				</select>
			</td>
		</tr>
	<?php
    if ( current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_reserve_to_other_patient') ) : ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Patient','tamarji'), "post[reservation][patient_id]") ?>
			</th>
			<td>
				<select name="post[reservation][patient_id]" class="__required">
					<option value="0"><?php echo __('Patients','tamarji') ?></option>
					<?php
					// main user query
					$args  = array(
					    // search only for patient role
					    'role'      => 'Mnbaa_Tamarji_Patient_Role',
					    // order results by display_name
					    'orderby'   => 'ID',
					    // return all fields
					    'fields'    => 'all'
					);
					
					// Create the WP_User_Query object
					$wp_user_query = new WP_User_Query($args);

					// Get the results
					$patients = $wp_user_query->get_results();
					
					// check to see if we have users
					if (!empty($patients)) {
						 // loop trough each author
					    foreach ($patients as $patient) {
					        $patient_info = get_userdata($patient->ID);
							?>
					<option value="<?php echo $patient_info->ID?>" <?php echo($reservation->patient_id==$patient->ID)?'selected':''?>><?php echo $patient_info->display_name?></option>
							<?php
						}
					}?>
				</select>
			</td>
		</tr>
			
		<?php else: ?>
			<input type="hidden" name="post[reservation][patient_id]" value="<?php echo get_current_user_id(); ?>" />
		<?php endif ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Department','tamarji'), "post[reservation][department_id]") ?>
			</th>
			<td>
				<?php $args = array(
						'show_option_all'   => __('Departments', 'tamarji'),
						'orderby'           => 'ID', 
						'order'             => 'ASC',
						'show_count'        => 0,
						'hide_empty'        => 0,
						'echo'              => 1,
						'selected'          => $reservation->department_id,
						'hierarchical'      => 0, 
						'taxonomy'          => 'Department',
						'name'           	=> 'post[reservation][department_id]',
						'id'                => '',
						'class'             => 'department __required',
						'hide_if_empty'     => false,
					);
					wp_dropdown_categories( $args ); ?>
			</td>
		</tr>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Doctor','tamarji'), "post[reservation][doctor_id]") ?>
			</th>
			<td>
				<select name="post[reservation][doctor_id]" class="doctor">
					<option value="0"><?php echo __('Doctors','tamarji') ?></option>
					<?php
					// main user query
					$args  = array(
					    // search only for patient role
					    'role'      => 'Mnbaa_Tamarji_Doctor_Role',
					    // order results by display_name
					    'orderby'   => 'ID',
					    // return all fields
					    'fields'    => 'all',
						'meta_query'     => array(
							array(
								'key'       => 'department_id',
								'value'     => ($reservation->department_id)?$reservation->department_id:'0',
								'compare'   => '=',
								'type'      => 'NUMERIC',
							),
						),
					);
					
					// Create the WP_User_Query object
					$wp_user_query = new WP_User_Query($args);
	
					// Get the results
					$doctors = $wp_user_query->get_results();
					
					// check to see if we have users
					if (!empty($doctors)) {
						 // loop trough each author
					    foreach ($doctors as $doctor) {
					        $doctor_info = get_userdata($doctor->ID);
							?>
					<option value="<?php echo $doctor_info->ID?>" <?php echo ($reservation->doctor_id==$doctor->ID)?'selected':''?>><?php echo $doctor_info->display_name?></option>
							<?php
						}
					}?>
				</select>
			</td>
		</tr>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Attend On','tamarji'), "post[reservation][attendOn]") ?>
			</th>
			<td>
				<input type="text" id="attendOn" name="post[reservation][attendOn]" value="<?php echo $reservation->attendOn?>" class="__required" />
				<script type="text/javascript" charset="utf-8">
				jQuery(function() {
					var $ = jQuery;
					$("#attendOn").datepicker({
						changeMonth : true,
						onClose : function(selectedDate) {
							$("#attendOn").datepicker("option", "dateFormat", 'yy-mm-dd').focus();
						}
					});
					$("#attendOn").datepicker("option", "dateFormat", 'yy-mm-dd');
					$("#attendOn").datepicker("option", "minDate", "+0d");
					var $datepicker = $("#attendOn");
					$datepicker.datepicker();
					var date = '<?php echo $reservation->attendOn?>';
					$datepicker.datepicker('setDate',date);
				});
				</script>
				<style type="text/css" media="screen">
					#ui-datepicker-div {
						display: none;
					}
				</style>
			</td>
		</tr>
		<!-- =========================== Complaint =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Complaint','tamarji'), "post[reservation][complaint][description]") ?>
			</th>
			<td>
				<div>
					<?php Mnbaa_Tamarji_textarea('post[reservation][complaint]',$reservation->complaint);?>
          		</div>
			</td>
		</tr>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Note','tamarji'), "post[reservation][doctor_id]") ?>
			</th>
			<td>
				<?php Mnbaa_Tamarji_textarea('post[reservation][notes]',$reservation->notes);?>
			</td>
		</tr>
		<?php if (is_plugin_active('billing/billing.php')): ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Paid','tamarji'), "post[reservation][paid]") ?>
			</th>
			<td>
				<?php 
				$billing_tree 	= Billing::get_billing_tree($reservation->billing_id);
				$billing_sum 	= Billing::sum_billing($reservation->billing_id)->sum;
				$fully_paid		= ($reservation_type->price - $billing_sum) ? false : TRUE;
				if ( is_array($billing_tree) && !empty($billing_tree) ){ 
					foreach ($billing_tree as $billing) {
					?>
					<input type="text" name="" value="<?php echo $billing->value?>"  style="background-color: #<?php echo ($billing->value>0)? 'F5FFF6':'FFF5FE' ?>" disabled />
					<?php _e('on', 'tamarji') ?> <?php echo $billing->dateTime?><br />
				<?php 
					}
				}?>
				
				Total paid:
				<span class="redText" style="color: <?php echo ($fully_paid)? 'green':'red' ?>">
					<?php echo ($billing_sum)? $billing_sum : 0 ?>
				</span> / 
				<span class="greenText" style="color: green"><?php echo $reservation_type->price?></span>
				<br />
				
				<?php if (!$fully_paid): ?>
					<input type="number" name="post[reservation][paid]" value="" min="<?php echo (($billing_sum!=0)? '-':'') . $billing_sum?>" max="<?php echo $reservation_type->price - $billing_sum?>" />
				<?php endif ?>
			</td>
		</tr>
		<?php endif ?>
		<tr>
			<input type="hidden" id="lastModified" name="post[reservation][lastModified]" value="<?php echo date('Y-m-d H:i:s')?>"/>
		</tr>
	</table>
	
	
<!-- <?php
global $blog_id;
if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_patient') && current_user_can_for_blog($blog_id, 'manage_options') ):?>
	<input type="hidden" name="post[reservation][confirmedOn]" value=""/>
<?php else: ?>
	<input type="hidden" name="post[reservation][confirmedOn]" value="<?php echo date('Y-m-d H:i:s')?>"/>
<?php endif ?> -->
<?php if (!$reservation->confirmedOn): ?>
	<input type="hidden" name="post[reservation][history][1][patient_id]" value="<?php echo $reservation->patient_id;?>"/>
	<input type="hidden" name="post[reservation][history][1][doctor_id]" value="<?php echo $reservation->doctor_id;?>"/>
	<input type="hidden" name="post[reservation][history][1][department_id]" value="<?php echo $reservation->department_id;?>"/>
	<input type="hidden" name="post[reservation][history][1][reservedOn]" value="<?php echo $reservation->reservedOn;?>"/>
	<input type="hidden" name="post[reservation][history][1][attendOn]" value="<?php echo $reservation->attendOn;?>"/>
	<input type="hidden" name="post[reservation][history][1][cancelledOn]" value="<?php echo $reservation->cancelledOn;?>"/>
	<input type="hidden" name="post[reservation][history][1][confirmedOn]" value="<?php echo $reservation->confirmedOn;?>"/>
	<input type="hidden" name="post[reservation][history][1][notes]" value="<?php echo $reservation->notes;?>"/>
<?php endif ?>

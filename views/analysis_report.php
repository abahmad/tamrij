<form action="" method="post" enctype="multipart/form-data">
	<?php if ($_GET['id']): ?>
		<input type="hidden" name="id" value="<?php echo $object->ID?>" />
	<?php endif ?>
	<table class="form-table">
		
	<?php
    if ( current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_analysis_report_to_other_patient') ) : ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Patient','tamarji'), "patient_id") ?>
			</th>
			<td>
				<select name="patient_id" class="__required">
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
			<input type="hidden" name="patient_id" value="<?php echo get_current_user_id(); ?>" />
		<?php endif ?>
		
		<!-- =========================== Analysis =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Analysis Report','tamarji'), "post[analysis_report]") ?>
			</th>
			<td>
				<div id="autoCompleteArea">
					<ul class="token-input-list analysis_report-list <?php echo '________hexaColorFunction________' ?>">
						<li class="token-input-token-hidden">
							<p></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="" value="" class="analysis_id" />
							<input type="text" name="" value="" class="value" />
						</li>
					</ul>
	          		
					<input type="text" id="analysis_report" class="autoComplete_analysis_report" />
          		</div>
			</td>
			<td>
			</td>
		</tr>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" id="button" value="<?php _e('Save','tamarji');?>" class="button button-primary" /></p>
</form>
<hr />
	<?php $visit = get_post()?>
	<?php $reservation = ($_GET['reservation_id']) ? get_post($_GET['reservation_id']) : get_post($visit->reservation_id)?>
	<input type="hidden" name="post[visit][reservation_id]" value="<?php echo $reservation->ID?>" />
	<input type="hidden" name="post[visit][department_id]" value="<?php echo $reservation->department_id?>" />
	<input type="hidden" name="post[visit][doctor_id]" value="<?php echo $reservation->doctor_id?>" />
	<input type="hidden" name="post[visit][patient_id]" value="<?php echo $reservation->patient_id?>" />
	<table class="form-table">
		
		<!-- =========================== Complaint =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Complaint','tamarji'), "post[visit][complaint][description]") ?>
			</th>
			<td>
				<div>
					<textarea name="post[visit][complaint]" cols="53"><?php echo get_post_meta($visit->ID, 'complaint', true) ?></textarea>
          		</div>
			</td>
		</tr>
		<!-- =========================== symptom =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Symptoms','tamarji'), "post[visit][symptom][description]") ?>
			</th>
			<td>
				<div id="autoCompleteArea">
					<ul class="token-input-list <?php echo ($visit->hide_symptom)?'hidden-ul':''?>">
						
						<?php foreach (get_post_meta($visit->ID, 'symptom') as $meta_value): ?>
						<li class="token-input-token">
							<p><?php echo $meta_value?></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[visit][symptom][]" value="<?php echo $meta_value?>" />
						</li>
						<?php endforeach ?>
						
						<li class="token-input-token-hidden">
							<p></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[visit][symptom][]" />
						</li>
	          		
					</ul>
	          		
					<input type="text" id="symptom" class="autoComplete" />
          		</div>
			</td>
			<td>
				<input type="checkbox" name="post[visit][hide_symptom]" value="1" id="hide_symptom" class="check_hide" <?php echo ($visit->hide_symptom)?'checked':''?> />
				<label for="hide_symptom"><?php _e('Hide','tamarji');?></label>
			</td>
		</tr>
		<!-- =========================== Diagnosis =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Diagnosis','tamarji'), "post[visit][diagnosis][description]") ?>
			</th>
			<td>
				<div id="autoCompleteArea">
					<ul class="token-input-list <?php echo ($visit->hide_diagnosis)?'hidden-ul':''?>">
						
						<?php foreach (get_post_meta($visit->ID, 'diagnosis') as $meta_value): ?>
						<li class="token-input-token">
							<p><?php echo $meta_value?></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[visit][diagnosis][]" value="<?php echo $meta_value?>" />
						</li>
						<?php endforeach ?>
						
						<li class="token-input-token-hidden">
							<p></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[visit][diagnosis][]" />
						</li>
					</ul>
	          		
					<input type="text" id="diagnosis" class="autoComplete" />
          		</div>
			</td>
			<td>
				<input type="checkbox" name="post[visit][hide_diagnosis]" value="1" id="hide_diagnosis" class="check_hide" <?php echo ($visit->hide_diagnosis)?'checked':''?> />
				<label for="hide_diagnosis"><?php _e('Hide','tamarji');?></label>
			</td>
		</tr>
		<!-- =========================== Analysis =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Analysis Report','tamarji'), "post[analysis_report]") ?>
			</th>
			<td>
				<div id="autoCompleteArea">
					<?php
					$args = array(
					    'meta_query' => array(
					        array(
					            'key' => 'parent_id',
					            'value' => $visit->ID
					        )
					    ),
					    'post_type' => 'analysis_report',
					    'posts_per_page' => 999999
					);
					$analysis_reports = get_posts($args);?>
					<ul class="token-input-list analysis_report-list <?php echo '________hexaColorFunction________' ?>">
						
						<?php foreach ($analysis_reports as $k => $analysis_report): ?>
							<li>
							<input type="hidden" name="post[delete][]" value="<?php echo $analysis_report->ID?>" /></li>
						<li class="token-input-token">
							<p><?php echo get_post($analysis_report->analysis_id)->analysis_name ?></p>
							<p class="desc"><?php echo $analysis_report->value ?></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[analysis_report][<?php echo $k+9999?>][analysis_id]" value="<?php echo $analysis_report->analysis_id?>" />
							<input type="hidden" name="post[analysis_report][<?php echo $k+9999?>][value]" value="<?php echo $analysis_report->value?>" />
							<input type="hidden" name="post[analysis_report][<?php echo $k+9999?>][patient_id]" value="<?php echo $visit->patient_id?>" />
						</li>
						<?php endforeach ?>
						
						<li class="token-input-token-hidden">
							<p></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="" value="" class="analysis_id" />
							<input type="text" name="" value="" class="value" />
							<input type="hidden" name="" value="<?php echo $reservation->patient_id ?>" class="patient_id" />
						</li>
					</ul>
	          		
					<input type="text" id="analysis_report" class="autoComplete_analysis_report" />
          		</div>
			</td>
			<td>
			</td>
		</tr>
		<!-- =========================== Prescription =========================== -->
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Prescription','tamarji'), "post[visit][prescription][description]") ?>
			</th>
			<td>
				<div id="autoCompleteArea">
					<ul class="token-input-list prescription-list">
						
						<?php foreach (get_post_meta($visit->ID, 'prescription', false) as $k => $meta_value):
							$meta_value = unserialize($meta_value)
						?>
						<li class="token-input-token">
							<p><?php echo $meta_value['name'] ?></p>
							<p class="desc"><?php echo $meta_value['description'] ?></p>
							<span class="token-input-delete-token">×</span>
							<input type="hidden" name="post[visit][prescription][<?php echo $k+9999 ?>][name]" value="<?php echo $meta_value['name'] ?>" />
							<input type="hidden" name="post[visit][prescription][<?php echo $k+9999 ?>][description]" value="<?php echo $meta_value['description'] ?>" />
						</li>
						<?php endforeach ?>
						
						<li class="token-input-token-hidden">
							<p></p>
							<span class="token-input-delete-token">×</span>
							<input type="text" name="" />
							<input type="hidden" name="" />
						</li>
	          		
					</ul>
	          		
					<input type="text" name="" class="autoComplete" id="prescription" />
          		</div>
			</td>
			<td>
				<?php if (get_post_meta($visit->ID, 'prescription')):?>
				<input type="submit" accesskey="p" name="save" value="<?php _e('Print Prescription','tamarji');?>" class="add-new-h2" id="print" />
				<?php endif ?>
			</td>
		</tr>
		
		<!-- =========================== Analysis =========================== -->
	</table>

	<?php
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_scan_prescription'))
		Mnbaa_tamarji_scan_div($visit->image_id);?>
	
	
	
<script>
	// delete li
	// function delete_li(e) {
		// $(e).closest('li').remove();
	// }
	
	$('#print').click( function () {
		var html = $('.prescription-list').clone();
		html.find('.token-input-delete-token').remove();
		html = '<ul class="token-input-list prescription-list">'+html.html()+'</ul>';
		html += '<style type="text/css" media="screen" id="style">'+$('style#style').html();+'</style>';
		var printWin = window.open('','','left=0,top=0,width=624,height=600,toolbar=0,scrollbars=0,status  =0');
		printWin.document.write(html);
		printWin.document.close();
		printWin.focus();
		printWin.print();
		printWin.close();
		
		if (!confirm('Do you want to save?'))
	    	return false;
	})
</script>
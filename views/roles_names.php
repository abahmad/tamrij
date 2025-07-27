<form action="" method="post" enctype="multipart/form-data">
	<table class="form-table">
		<?php foreach ($roles as $k => $v): ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__(ucfirst($k),'tamarji'), "roles[$k]") ?>
			</th>
			<td>
				<select name='roles[<?php echo$k?>]'>
					<option value="0">---</option>
   					<?php wp_dropdown_roles($v); ?>
				</select>
			</td>
		</tr>
		<?php endforeach ?>
		
	</table>
	<p class="submit">
		<input type="submit" name="Submit" id="button" value="<?php _e('Save','tamarji');?>" class="button button-primary" /></p>
</form>
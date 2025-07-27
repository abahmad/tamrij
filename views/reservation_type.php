<?php $type = get_post()?>
	<table class="form-table">
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Name','tamarji'), "post[reservation_type][type_name]") ?>
			</th>
			<td>
				<div>
					<?php Mnbaa_Tamarji_input('post[reservation_type][type_name]',$type->type_name);?>
          		</div>
			</td>
		</tr>
		<?php if ( is_plugin_active('billing/billing.php') ) : ?>
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Price','tamarji'), "post[reservation_type][price]") ?>
			</th>
			<td>
				<input type="number" name="post[reservation_type][price]" value="<?php echo $type->price?>" />
			</td>
		</tr>
		<?php endif ?>
		
		<tr>
		<input type="hidden" id="lastModified" name="post[reservation_type][lastModified]" value="<?php echo date('Y-m-d H:i:s')?>"/>
		</tr>
	</table>
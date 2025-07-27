<?php if(isset($error_msg)){ ?>
    <div class="error">
         <p><?php echo $error_msg; ?></p>
    </div>
<?php } ?>
<form action="" method="post" enctype="multipart/form-data">
	<?php if ($_GET['id']): ?>
		<input type="hidden" name="id" value="<?php echo $object->ID?>" />
	<?php endif ?>
	<table class="form-table">
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Username','tamarji'), "atributes[user_login]") ?>
			</th>
			<td>
				<?php 
				if ($_GET['id'])
					Mnbaa_Tamarji_input_read_only("atributes[user_login]", ($_POST['atributes']['user_login'] != "") ? $_POST['atributes']['user_login'] : $object -> user_login );
				else 
					Mnbaa_Tamarji_input("atributes[user_login]", ($_POST['atributes']['user_login'] != "") ? $_POST['atributes']['user_login'] : $object -> user_login , '__required');
					
				?>*
			</td>
		</tr>
        
		<tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Full Name','tamarji'), "atributes[display_name]") ?>
			</th>
			<td>
				<?php Mnbaa_Tamarji_input("atributes[display_name]", ($_POST['atributes']['display_name'] != "") ? $_POST['atributes']['user_login'] : $object -> display_name , '__required')?>*
			</td>
		</tr>
        
        <tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Gender','tamarji'), "atributes[gender]")?>
			</th>
			<td>
                <input type="radio" name="atributes[gender]"  value="0" <?php if ($object->gender == '0' || $_POST['atributes']['gender'] == '0') {?> checked <?php }?>><?php echo __('Male','tamarji')?>
                <input type="radio" name="atributes[gender]"  value="1" <?php if ($object->gender == '1' || $_POST['atributes']['gender'] == '1') {?> checked <?php }?>><?php echo __('Female','tamarji')?>
			</td>
		</tr>
        
        <tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('National_id','tamarji'), "atributes[national_id]") ?>
			</th>
			<td>
				<?php Mnbaa_Tamarji_input("atributes[national_id]",($_POST['atributes']['national_id'] != "") ? $_POST['atributes']['national_id'] : $object -> national_id , '__required') ?>*
			</td>
		</tr>
        
        <tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Email','tamarji'), "atributes[user_email]") ?>
			</th>
			<td>
				<?php Mnbaa_Tamarji_input("atributes[user_email]", ($_POST['atributes']['user_email'] != "") ? $_POST['atributes']['user_email'] : $object -> user_email , '__required') ?>*
			</td>
		</tr>
        
        <tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('birthDate','tamarji'), "atributes[birthDate]") ?>
			</th>
			<td>
				<input type="text" id="Mnbaa_Tamarji_BirthDate" name="atributes[birthDate]" value="<?php if($_POST['atributes']['birthDate'] != ""){ echo $_POST['atributes']['birthDate'];} else echo $object -> birthDate?>"/>
			</td>
		</tr>

         <tr>
			<th>
				<?php Mnbaa_Tamarji_label(__('Phones','tamarji'), "Phones") ?>
			</th>
			<td>
	            <div id="phones">
	            	<?php
	            	if ($_GET['id']) {
	            		$usermeta_phones = get_usermeta($_GET['id'], 'phone');
						$phones = array();
						if (!is_array($usermeta_phones))
							$phones[] = $usermeta_phones;
						else
							$phones = $usermeta_phones;
						
	            		foreach ($phones as $k => $phone):
	            			$phone_slices = explode('__', $phone);
	            	?>
					<div id="phone">
						<input type="hidden" name="atributes[oldphone][<?php echo $k?>][prev_data]" value="<?php echo $phone?>" size="5" />
						<input type="text" name="atributes[oldphone][<?php echo $k?>][prefix]" value="<?php echo $phone_slices[0]?>" size="5" />
		    			<input type="text" name="atributes[oldphone][<?php echo $k?>][number]" value="<?php echo $phone_slices[1]?>" />
		                <select name="atributes[oldphone][<?php echo $k?>][type]">
							<option value="0"><?php echo __("line",'tamarji');?></option>
		                    <option value="1" test="<?php echo $phone_slices[2] ?>" <?php if($phone_slices[2]) echo "selected"?>><?php echo __("mobile",'tamarji');?></option>
						</select>
	          		</div>
					<?php endforeach;
					}?>
	            	<div id="phone1">
						<input type="text" name="atributes[phone][1][prefix]" size="5" />
		    			<input type="text" name="atributes[phone][1][number]" />
		                <select name="atributes[phone][1][type]">
							<option>- - -</option>
							<option value="0"><?php echo __("line",'tamarji');?></option>
		                    <option value="1"><?php echo __("mobile",'tamarji');?></option>
						</select>
	          		</div>  
   
	          	</div>      
                <a href="javascript:void(0);" onclick="add_phone()"><?php echo __("add",'tamarji');?> </a>
			
				<script>
				var count=1;
				function add_phone(){
					count++;
					var DIV = '<div id="phone'+count+'"><input type="text" name="atributes[phone]['+count+'][prefix]" size="5" /> <input type="text" name="atributes[phone]['+count+'][number]"  /> <select name="atributes[phone]['+count+'][type]"><option>- - -</option> <option value="0"><?php echo __("line","tamarji");?></option> <option value="1"><?php echo __("mobile","tamarji");?></option> </select> <a href="javascript:void(0);" id="'+count+'" onclick="delete_phone(this.id)"><?php echo __("Delete","tamarji"); ?> </a></div>';
					
					jQuery('#phones').append(DIV);
					jQuery('#phone'+count+' input:first').focus();
					
				}
				function delete_phone(id) {
					 
				  	var checkstr =  confirm('are you sure you want to delete this?');
				    if(checkstr == true){
						//alert(id);
						count--;
						jQuery("#phone"+id).remove();
						// jQuery('#count').val(count);
				    }else{
				    	return false;
				    }
				}
				
				
				</script>
			</td>
		</tr>
		
	</table>
	<p class="submit">
		<input type="submit" name="Submit" id="button" value="<?php _e('save','tamarji')?>" class="button button-primary" /></p>
</form>
<hr />
<?php
global $rows_per_page, $wp_rewrite;

/*
 * We start by doing a query to retrieve all users
 * We need a total user count so that we can calculate how many pages there are
 */

$count_args  = array(
    'role'      => get_option('Mnbaa_Tamarji_Patient_Role'),
    'fields'    => 'all_with_meta',
    'number'    => 999999      
);
$user_count_query = new WP_User_Query($count_args);
$user_count = $user_count_query->get_results();

// count the number of users found in the query
$total_users = $user_count ? count($user_count) : 1;

// grab the current page number and set to 1 if no page number is set
$page = isset($_GET['p']) ? $_GET['p'] : 1;

// how many users to show per page
$users_per_page = $rows_per_page;

// calculate the total number of pages.
$total_pages = 1;
$offset = $users_per_page * ($page - 1);
$total_pages = ceil($total_users / $users_per_page);


// main user query
$args  = array(
    // search only for patient role
    'role'      => get_option('Mnbaa_Tamarji_Patient_Role'),
    // order results by display_name
    'orderby'   => 'ID',
    // return all fields
    'fields'    => 'all_with_meta',
    'number'    => $users_per_page,
    'offset'    => $offset // skip the number of users that we have per page  
);

// Create the WP_User_Query object
$wp_user_query = new WP_User_Query($args);

// Get the results
$patients = $wp_user_query->get_results();

// check to see if we have users
if (!empty($patients)) {
 	?>
 <table class="wp-list-table widefat fixed posts">
	<thead>
		<tr>
			<th><?php echo __("Name","tamarji");?></th>
			<th><?php echo __("National ID","tamarji");?></th>
			<th><?php echo __("Phone","tamarji");?></th>
			<th><?php echo __("Edit","tamarji");?></th>
		</tr>
	</thead>
	<tbody id="the-list">
		<?php
    // loop trough each author
    foreach ($patients as $patient) {
        $patient_info = get_userdata($patient->ID);
        $alternate = TRUE?>
		
		<tr class="<?php if ($alternate) echo 'alternate'?>">
			<td><?php echo $patient_info->display_name?></td>
			<td><?php echo $patient_info->national_id?></td>
			<td><?php
				foreach (get_user_meta($patient_info->ID, 'phone', false) as $phone) {
					$phone_pieces = explode('__', $phone);
					echo $phone_pieces[0] . ' ' . $phone_pieces[1] . ' [ ' . ($phone_pieces[3] ? 'mob':'line') . ' ]<br />';
				}
				?></td>
			<td><a href="admin.php?page=<?php echo $_REQUEST['page'];?>&id=<?php echo $patient_info->ID?>"><?php _e('Edit','tamarji');?></a></td>
		</tr>
		<?php
		$alternate = ($alternate) ? FALSE :TRUE;
	}?>
	</tbody>
</table>
<?php
} else {
    echo _e('No authors found','tamarji');
}

// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('p', $query_string) . '%_%';

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';

echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
    'prev_text' => __('Previous','tamarji'), // text for previous page
    'next_text' => __('Next','tamarji'), // text for next page
    'total' => $total_pages, // the total number of pages we have
    'current' => $page, // the current page
    'end_size' => 1,
    'mid_size' => 5,
));
?>
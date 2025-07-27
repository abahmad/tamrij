<?php
// Delete
if ($_GET['delete']) {
	update_user_meta( $_GET['delete'], 'active', 0, false );
}

// check if submited
if (isset($_POST['Submit'])) {
	// check if name is empty
	if ($_POST['id']) {
		// Edit
		$userdata = array(
		    'ID'			=>  $_POST['id'],
		    'user_login'	=>  $_POST['atributes']['user_login'],
		    // 'user_url'   	=>  $_POST['atributes']['user_url'],
		    'user_email' 	=>  $_POST['atributes']['user_email'],
		    'display_name' 	=>  $_POST['atributes']['display_name'],
		);
		$user_id = wp_update_user( $userdata ) ;
		
		// Add Cap
		$user = new WP_User( $user_id);
		
		//On success
		if( !is_wp_error($user_id) ) {
			update_user_meta( $user_id, 'department_id', $_POST['atributes']['department_id'], false );
			if (is_array($_POST['atributes']['oldphone'])) {
				foreach ($_POST['atributes']['oldphone'] as $k => $oldphone) {
					if (strlen($oldphone['prefix']) && strlen($oldphone['number'])) {
						$newphone = $oldphone['prefix'] . '__' . $oldphone['number'] . '__' . $oldphone['type'];
						update_user_meta( $user_id, 'phone', $newphone, $oldphone['prev_data'] );
					}
				}
			}
			foreach ($_POST['atributes']['phone'] as $k => $phone) {
				if (strlen($phone['prefix']) && strlen($phone['number'])) {
					$newphone = $phone['prefix'] . '__' . $phone['number'] . '__' . $phone['type'];
					add_user_meta( $user_id, 'phone', 	$newphone, false );
				}
			}
		}
	} else {
		// Add
		$userdata = array(
		    'role'			=> get_option('Mnbaa_Tamarji_Assistant_Role'),
		    'user_login'	=> $_POST['atributes']['user_login'],
		    // 'user_url'   	=> $_POST['atributes']['user_url'],
		    'user_pass' 	=> wp_generate_password(),
		    'user_email' 	=> $_POST['atributes']['user_email'],
		    'display_name' 	=> $_POST['atributes']['display_name'],
		);
		$user_id = wp_insert_user( $userdata ) ;
		$user = new WP_User( $user_id);
		$errors=$user->errors;
		if($errors){
			foreach ($errors as  $error) {
				echo '<div id="message" class="error"><p>'.__($error[0],"tamarji").'</p></div>';
			}
		}else{
			echo '<div id="message" class="updated"><p>'.__("Successfully Added","tamarji").'</p></div>';
			
		}
		//On success
		if( !is_wp_error($user_id) ) {
			add_user_meta( $user_id, 'department_id', $_POST['atributes']['department_id'], false );
			add_user_meta( $user_id, 'active', 1, true );
			foreach ($_POST['atributes']['phone'] as $k => $phone) {
				if (strlen($phone['prefix']) && strlen($phone['number'])) {
					$newphone = $phone['prefix'] . '__' . $phone['number'] . '__' . $phone['type'];
					add_user_meta( $user_id, 'phone', 	$newphone, false );
				}
			}
		}
	}
}

// get by id
$object = get_userdata($_GET['id']);

// include view
include (plugin_dir_path(__DIR__) . 'views/assistant.php');
?>
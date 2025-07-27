<?php

// check if submited
if (isset($_POST['Submit'])) {
	// create option role name
	function Mnbaa_Tamarji_get_role_option($name) {
		$x = 'Mnbaa_Tamarji_' . ucfirst($name) . '_Role';
		return $x;
	}
	

	
	if (is_array($_POST['roles'])){
		foreach ($_POST['roles'] as $name => $value) {
			if ( get_option( Mnbaa_Tamarji_get_role_option($name) ) )
				update_option( Mnbaa_Tamarji_get_role_option($name), $value );
			else
				add_option( Mnbaa_Tamarji_get_role_option($name), $value );
		}
	}

}

// get roles names
$roles = array();
$roles['doctor'] 	= get_option('Mnbaa_Tamarji_Doctor_Role');
$roles['assistant'] = get_option('Mnbaa_Tamarji_Assistant_Role');
$roles['patient'] 	= get_option('Mnbaa_Tamarji_Patient_Role');

// include view
include (plugin_dir_path(__DIR__) . 'views/roles_names.php');
?>
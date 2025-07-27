<?php
/*
 Plugin Name: tamarji
 Plugin URI:
 Description: WP blugin fom tamarji  .
 Author: mnbaa
 Author URI: http://www.mnbaa.com
 Version: 1.0
 Text Domian: tamarji
 Domain Path: /languages/
 */
 $rows_per_page = 10;
//load arrays
include (plugin_dir_path(__FILE__) . 'helpers/arrays.php');
// 
// //load  contoller file which called by ajax
include (plugin_dir_path(__FILE__) . 'controllers/ajax_functions.php');
// 
// //load helper
include (plugin_dir_path(__FILE__) . 'helpers/mnbaa_functions.php');
include (plugin_dir_path(__FILE__) . 'helpers/wp_functions.php');
// 
// //load form_validation library
include (plugin_dir_path(__FILE__) . 'libraries/form_validation.php');


// scan
wp_enqueue_script('image-js', plugins_url( '', __FILE__ ).'/views/js/image-js.js');
add_action( 'admin_enqueue_scripts', 'load_admin_things' );
function load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

// scan page
function Mnbaa_tamarji_scan_div($meta_image_url){

    if ($meta_image_url) {
    	$image = wp_get_attachment_image_src($meta_image_url, 'medium');
    	$image = $image[0];
	}
?>
<div class="Mnbaa_tamarji_scan">
	<input name="post[image_id]" type="hidden" class="custom_upload_image" value="<?php echo $meta_image_url?>" />
	<input name="post[image_url]" type="hidden" class="custom_upload_image_url" value="<?php echo $image?>" />
    <img src="<?php echo $image?>" class="custom_preview_image small" alt="" /><br />
    <input class="custom_upload_image_button button" type="button" value="Choose Image" />
    <small> <a href="#" class="custom_clear_image_button">Remove Image</a></small>
</div>
<?php
}

//call actions on initialization
Mnbaa_tamarji_RunPlugin();

function Mnbaa_tamarji_add_caps() {
	global $custom_posts;
	
	// Doctor Caps
	$role = get_role('administrator');
	
	$role->add_cap('Mnbaa_Tamarji_doctor');
	$role->add_cap('Mnbaa_Tamarji_assistant');
	$role->add_cap('Mnbaa_Tamarji_patient');
	
	$role->add_cap('Mnbaa_Tamarji_reserve_to_other_patient');
	$role->add_cap('Mnbaa_Tamarji_cancel_reservation');
	$role->add_cap('Mnbaa_Tamarji_delay_reservation');
	$role->add_cap('Mnbaa_Tamarji_confirm_reservation');
	$role->add_cap('Mnbaa_Tamarji_view_patients_files');
	$role->add_cap('Mnbaa_Tamarji_edit_doctors');
	$role->add_cap('Mnbaa_Tamarji_edit_assistants');
	$role->add_cap('Mnbaa_Tamarji_edit_patients');
	$role->add_cap('Mnbaa_Tamarji_edit_roles_names');
	
	$role->add_cap('Mnbaa_Tamarji_scan_prescription');
	$role->add_cap('Mnbaa_Tamarji_online_reserve');
	
	$role->add_cap('Mnbaa_tamarji_manage_departments');
	
	foreach ($custom_posts as $post_type => $useless) {
		$role->add_cap('Mnbaa_Tamarji_edit_'.$post_type);
		$role->add_cap('Mnbaa_Tamarji_edit_'.$post_type.'s');
		$role->add_cap('Mnbaa_Tamarji_edit_others_'.$post_type.'s');
		$role->add_cap('Mnbaa_Tamarji_edit_published_'.$post_type.'s');
		$role->add_cap('Mnbaa_Tamarji_publish_'.$post_type.'s');
		$role->add_cap('Mnbaa_Tamarji_read_'.$post_type);
		$role->add_cap('Mnbaa_Tamarji_read_private_'.$post_type.'s');
	}
}
add_action('init','Mnbaa_tamarji_add_caps');

function add_roles_on_plugin_activation() {
	add_role( 'Mnbaa_Tamarji_Doctor_Role', __('Doctor','tamarji'));
	add_role( 'Mnbaa_Tamarji_Assistant_Role', __('Assistant','tamarji'));
	add_role( 'Mnbaa_Tamarji_Patient_Role', __('Patient','tamarji'));
}
register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


function add_roles_on_plugin_deactivation() {
	remove_role('Mnbaa_Tamarji_Assistant_Role');
	remove_role('Mnbaa_Tamarji_Assistant_Role');
	remove_role('Mnbaa_Tamarji_Patient_Role');
	
	$role = get_role('administrator');
	
	$role->remove_cap('Mnbaa_Tamarji_doctor');
	$role->remove_cap('Mnbaa_Tamarji_assistant');
	$role->remove_cap('Mnbaa_Tamarji_patient');
	
	$role->remove_cap('Mnbaa_Tamarji_edit_dept_reservations_not_assigned_to_doctor');
	$role->remove_cap('Mnbaa_Tamarji_reserve_to_other_patient');
	$role->remove_cap('Mnbaa_Tamarji_confirm_reservation');
	$role->remove_cap('Mnbaa_Tamarji_cancel_reservation');
	$role->remove_cap('Mnbaa_Tamarji_delay_reservation');
	$role->remove_cap('Mnbaa_Tamarji_view_patients_files');
	$role->remove_cap('Mnbaa_Tamarji_edit_doctors');
	$role->remove_cap('Mnbaa_Tamarji_edit_assistants');
	$role->remove_cap('Mnbaa_Tamarji_edit_patients');
	$role->remove_cap('Mnbaa_Tamarji_edit_roles_names');
	
	$role->remove_cap('Mnbaa_Tamarji_scan_prescription');
	$role->remove_cap('Mnbaa_Tamarji_online_reserve');
	
	foreach ($custom_posts as $post_type => $useless) {
		$role->remove_cap('Mnbaa_Tamarji_edit_'.$post_type);
		$role->remove_cap('Mnbaa_Tamarji_edit_'.$post_type.'s');
		$role->remove_cap('Mnbaa_Tamarji_edit_others_'.$post_type.'s');
		$role->remove_cap('Mnbaa_Tamarji_edit_published_'.$post_type.'s');
		$role->remove_cap('Mnbaa_Tamarji_publish_'.$post_type.'s');
		$role->remove_cap('Mnbaa_Tamarji_read_'.$post_type);
		$role->remove_cap('Mnbaa_Tamarji_read_private_'.$post_type.'s');
	}
}
register_deactivation_hook( __FILE__, 'add_roles_on_plugin_deactivation' );


// =============================================================================================================
// Register Custom Taxonomy		'parent_item'                => __( 'Parent department', 'tamarji' ),

function Mnbaa_Tamarji_Department() {
	//echo"hoda";

	$labels = array(
		'name'                       => _x( 'Departments', 'Taxonomy General Name', 'tamarji' ),
		'singular_name'              => _x( 'Department', 'Taxonomy Singular Name', 'tamarji' ),
		'menu_name'                  => __( 'Department', 'tamarji' ),
		'all_items'                  => __( 'All department', 'tamarji' ),
		'parent_item_colon'          => __( 'Parent department:', 'tamarji' ),
		'new_item_name'              => __( 'New department Name', 'tamarji' ),
		'add_new_item'               => __( 'Add New department', 'tamarji' ),
		'edit_item'                  => __( 'Edit department', 'tamarji' ),
		'update_item'                => __( 'Update department', 'tamarji' ),
		'separate_items_with_commas' => __( 'Separate departments with commas', 'tamarji' ),
		'search_items'               => __( 'Search departments', 'tamarji' ),
		'add_or_remove_items'        => __( 'Add or remove departments', 'tamarji' ),
		'choose_from_most_used'      => __( 'Choose from the most used departments', 'tamarji' ),
		'not_found'                  => __( 'Not Found', 'tamarji' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		
		'capabilities' 	=> array(
			'manage_terms' => 'Mnbaa_tamarji_manage_departments',
			'edit_terms'   => 'Mnbaa_tamarji_manage_departments',
			'delete_terms' => 'Mnbaa_tamarji_manage_departments',
			'assign_terms' => 'edit_posts',
	    ),
	);
	register_taxonomy( 'Department', '', $args );

}

// Hook into the 'init' action
add_action( 'init', 'Mnbaa_Tamarji_Department', 0 );


$mnbaa_tamarji_prfx = 'Mnbaa_Tamarji_';
// $post_types = array('reservation', 'visit');


add_action('admin_menu', 'my_admin_menu'); 
function my_admin_menu() {
	global $blog_id;
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_reservations')){
	    add_submenu_page('tamarji', 'Reservation',__('Reservation','tamarji'),'Mnbaa_Tamarji_edit_reservations', 'edit.php?post_type=reservation');
	}
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_vists')){
	    add_submenu_page(null, 'Visit', 'Visit', 'Mnbaa_Tamarji_doctor', 'edit.php?post_type=visit');
	}
	add_submenu_page('tamarji', 'Reservation Type',__('Reservation Type','tamarji'),'Mnbaa_Tamarji_edit_reservation_types', 'edit.php?post_type=reservation_type');
}

add_action( 'init', 'create_post_types' );
function create_post_types() {
  
	global $custom_posts;
	$supports = array ('');
	
	foreach ($custom_posts as $post_type => $useless) {
		$args = array(
				'labels' => array(
				    'name' => __( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
				    'singular_name' => __( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
		            'edit_item' => __('Edit','tamarji') .' '. __( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
		            'new_item' => __('New','tamarji')  .' '. __( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
		            'all_items' => __('All','tamarji') . ' '. __( ucfirst( str_replace('_', ' ', $post_type) ) . 's', 'tamarji'),
		            'view_item' => __('View','tamarji') .' '. __( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
		            'add_new_item' => __('Add New','tamarji') .' '.__( ucfirst( str_replace('_', ' ', $post_type) ) , 'tamarji'),
		            'add_new' => __('Add New','tamarji'),
				  ),
				'supports' => $supports,
				'public' => true,
				'has_archive' 	=> true,
				'show_in_menu' 	=> 'edit.php?post_type='.$post_type,
				'capability_type'=>$post_type,
			    'capabilities' 	=> array(
			        'edit_post'				 => "Mnbaa_Tamarji_edit_{$post_type}",
					'read_post'		 		 => "Mnbaa_Tamarji_read_{$post_type}",
					'delete_post'			 => "Mnbaa_Tamarji_delete_{$post_type}",
					'edit_posts'		 	 => "Mnbaa_Tamarji_edit_{$post_type}s",
					'edit_others_posts'	 	 => "Mnbaa_Tamarji_edit_others_{$post_type}s",
					'publish_posts'		 	 => "Mnbaa_Tamarji_publish_{$post_type}s",
					'read_private_posts'	 => "Mnbaa_Tamarji_read_private_{$post_type}s",
			        'delete_posts'           => "Mnbaa_Tamarji_delete_{$post_type}s",
			        'delete_private_posts'   => "Mnbaa_Tamarji_delete_private_{$post_type}s",
			        'delete_published_posts' => "Mnbaa_Tamarji_delete_published_{$post_type}s",
			        'delete_others_posts'    => "Mnbaa_Tamarji_delete_others_{$post_type}s",
			        'edit_private_posts'     => "Mnbaa_Tamarji_edit_private_{$post_type}s",
			        'edit_published_posts'   => "Mnbaa_Tamarji_edit_published_{$post_type}s",
			    ),
			    // as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly 
			    'map_meta_cap' => true
			);
		if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_patient') && !current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_online_reserve') && !current_user_can_for_blog($blog_id, 'manage_options')) {
			$args['capabilities']['create_posts'] = false;
		}
		register_post_type( $post_type, $args);
	}
}
?>
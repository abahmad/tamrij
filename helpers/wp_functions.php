<?php
/////.////////////////////////////////////  Valid  //.///////////////.////////////////////////////

// License key is valid

function register_session() {
	if( !session_id() )
		session_start();
}
// function get_roles() {
	// global $wp_roles;
	// $all_roles = $wp_roles->roles;
	// echo "<pre>";
	// print_r($all_roles);
	// echo "</pre>";
// }
// add_action( 'init', 'get_roles' );

function Mnbaa_tamarji_RunPlugin() {
	add_action('init','register_session');
	add_action('admin_menu', 'Mnbaa_tamarji_add_tamarji_page');
	add_action('admin_menu', 'Mnbaa_tamarji_sub_doctor_page');
	add_action('admin_menu', 'Mnbaa_tamarji_sub_assistant_page');
	add_action('admin_menu', 'Mnbaa_tamarji_sub_patient_page');
	add_action('admin_menu', 'Mnbaa_tamarji_patient_file_page');
	add_action('admin_menu', 'Mnbaa_tamarji_sub_patient_file');
	add_action('admin_menu', 'Mnbaa_tamarji_sub_department');
	// add_action('admin_menu', 'adjust_the_wp_menu', 999 );
	
    add_action('add_meta_boxes', 'post_types_add_meta_box');
    add_action('save_post', 'post_types_save_meta_box_data');
	
	add_filter('manage_edit-reservation_type_columns', 'add_new_reservation_type_columns');
	add_action('manage_reservation_type_posts_custom_column', 'manage_reservation_type_columns', '', 2);
	add_filter('manage_edit-reservation_columns', 'add_new_reservation_columns');
	add_action('manage_reservation_posts_custom_column', 'manage_reservation_columns', '', 2);
	 
	// Ajax Actions
	add_action('wp_ajax_getAnalysisByCategory', 'getAnalysisByCategory');
	add_action('wp_ajax_getDoctorsByDepartment', 'getDoctorsByDepartment');
	add_action('wp_ajax_get_visit_meta', 'get_visit_meta');
	add_action('wp_ajax_get_medical_analysis', 'get_medical_analysis');
	add_action('wp_ajax_confirm_reservation', 'confirm_reservation');
	add_action('wp_ajax_cancel_reservation', 'cancel_reservation');
	add_action('wp_ajax_reservations_shortcut_change_limit', 'reservations_shortcut_change_limit');
	
	//remove filter and search form tabel
	add_filter('bulk_actions-' . 'edit-reservation', '__return_empty_array' );
	add_filter('months_dropdown_results', '__return_empty_array');
	add_action('views_edit-reservation', 'remove_views' );
	
	if(!function_exists('wp_get_current_user')) {
    	include(ABSPATH . "wp-includes/pluggable.php");
	}
	
	
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_roles_names'))
		add_action('admin_menu', 'Mnbaa_tamarji_sub_roles_names');

	// Integrate with billing
	add_action('admin_menu', 'Mnbaa_tamarji_integrate_with_billing');
	
	// load styles & scripts
	add_action( 'admin_enqueue_scripts', 'Mnbaa_tamarji_admin_init' );
	
	// load languages
	add_action( 'plugins_loaded', 'Mnbaa_tamarji_textdomain' );
}


// remove links form  list table
function remove_views() {
    unset($views['all']);
    unset($views['publish']);
    unset($views['trash']);
    return $views;
	
	}

// add main nav menue
function Mnbaa_tamarji_add_tamarji_page(){
	global $blog_id;
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_doctor'))
		add_menu_page( '', __('Tamarji','tamarji'), 'Mnbaa_Tamarji_doctor', 'tamarji', 'tamarji_intro','');
	
	elseif(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_assistant'))
		add_menu_page( '', __('Tamarji','tamarji'), 'Mnbaa_Tamarji_assistant', 'tamarji', 'tamarji_intro','');
	
	elseif(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_patient'))
		add_menu_page( '', __('Tamarji','tamarji'), 'Mnbaa_Tamarji_patient', 'tamarji', 'tamarji_intro','');
}

///////////// Start Reservation List Table  ////////////
function add_new_reservation_type_columns($reservation_type_columns) {
	global $blog_id;
     
    $new_columns['type_name'] = __('Name','tamarji');
	if ( is_plugin_active('billing/billing.php') )
		$new_columns['price'] = __('Price','tamarji');

	$new_columns['actions'] = __('Actions','tamarji');

    return $new_columns;
}
function manage_reservation_type_columns($column_name, $id) {
	
	    global $wpdb;
		
		// get reservation
		$reservation_type = get_post($id);
		
	    switch ($column_name) {
	 
		    case 'type_name':
				echo $reservation_type->type_name;
		        break;
				
		    case 'price':
				echo $reservation_type->price;
		        break;
				
		    case 'actions':
		    	echo "<a href='post.php?post={$id}&action=edit' class='delay'>Edit</a> ";
		        break;
				
		    default:
		        break;
	    } // end switch
	}
function add_new_reservation_columns($reservation_columns) {
	global $blog_id;
     
    // $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['department'] = __('Department','tamarji');
   	$new_columns['reservation_type'] = __('Reservation Type','tamarji');
    $new_columns['doctor'] = __('Doctor','tamarji');
    $new_columns['patient'] = __('Patient','tamarji');
    $new_columns['attendOn'] = __('Attend On','tamarji');
	
	if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_visits'))
    	$new_columns['visit'] = __('Visit','tamarji');
	
	// $new_columns['complaint'] = __('Complaint','tamarji');
	
	$new_columns['actions'] = __('Actions','tamarji');
	if(is_plugin_active('billing/billing.php'))
		$new_columns['paid'] = __('Paid','tamarji');

 
    return $new_columns;
}
function manage_reservation_columns($column_name, $id) {
	
	    global $wpdb;
		
		// get reservation
		$reservation 		= get_post($id);
		$reservation_type	= get_post($reservation->type);
		
	    switch ($column_name) {
	 
		    case 'patient':
		        $patient =  get_userdata($reservation->patient_id);
				if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_view_patients_files'))
					echo "<a href='admin.php?page=patient_file&patient_id=$reservation->patient_id'>".$patient->display_name."</a>";
				else
					echo $patient->display_name;
		        break;
				
		    case 'department':
		    	$department =  get_term($reservation->department_id,'Department');
				echo $department->name;
				echo "<span id='confirmed_word' style='display:none'>" . __('Confirmed' , 'tamarji') . "</span>";
				echo "<span id='cancelled_word' style='display:none'>" . __('Cancelled' , 'tamarji') . "</span>";
				echo "<span id='edit_word' style='display:none'>" . __('Edit' , 'tamarji') . "</span>";
				echo "<span id='delay_word' style='display:none'>" . __('Delay' , 'tamarji') . "</span>";
		        break;
				
		    case 'reservation_type':
				echo $reservation_type->type_name;
		        break;
	
		    case 'doctor':
		        $x =  get_userdata($reservation->doctor_id);
				echo $x->display_name;
		        break;
	
		    case 'attendOn':
				echo $reservation->attendOn;
		        break;
	
		    case 'visit':
				$args = array(
					'post_type' => 'visit',
					'post_status'=>'publish',
			    	'meta_query' => array(
				        array(
				            'key' => 'reservation_id',
				            'value' => $id
				        	)
			    		)
					);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ){
					
					$query -> the_post();
					$post_id=$query -> post -> ID;
					echo "<a href='post.php?post=$post_id&action=edit&reservation_id=$id'>".__('Visit','tamarji')."</a>";
				}
				else echo "<a href='post-new.php?post_type=visit&reservation_id=$id'>".__('New Visit','tamarji')."</a>";
		        break;
				
		    case 'actions':
				$confirmed = ($reservation->confirmedOn)? 'confirmed':'';
				$cancelled= ($reservation->cancelledOn)? 'cancelled':'';
				
				if ($cancelled) {?>
					<span class='cancelled'><?php _e('cancelled','tamarji')?></span>
				<?php
				} else {
					if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_cancel_reservation')){
					echo "<a href='javascript:void(0);' data-id='{$id}' class='cancel_link'>".__('Cancel','tamarji')."</a> ";
					}
					
					$confirmed_word = ($confirmed)? __('Delay','tamarji') : __('Edit','tamarji');
					if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_delay_reservation')){
					echo "<a href='post.php?post={$id}&action=edit' class='delay'>" . $confirmed_word . "</a> ";
					}
					if ($confirmed) {
						echo "<span class='confirmed'>".__('Confirmed','tamarji')."</span> ";
					} else {
						if (current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_confirm_reservation')){
							echo "<a href='javascript:void(0);' data-id='{$id}' class='confirm_link '>".__('Confirm','tamarji')."</a> ";
						}
					}
				}
		        break;
			
			case 'complaint':
				echo "<span title='". $reservation->complaint ."'>". substr($reservation->complaint, 0, 50) ."</span>";
		        break;
				
			case 'paid':
				$billing = Billing::sum_billing($reservation->billing_id);
				echo ( ($billing->sum)? $billing->sum : 0 ) . ' / <span class="red">' . $reservation_type->price.'</span>';
		        break;
				
		    default:
		        break;
	    } // end switch
	}
///////////// END Reservation List Table  ////////////

function Mnbaa_tamarji_sub_doctor_page(){
	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_doctors')){
		add_submenu_page( 
	          'tamarji'   //or 'options.php' 
	        , 'doctor' 
	        , __('Doctors','tamarji')
	        , 'Mnbaa_Tamarji_edit_doctors'
	        , 'doctor'
	        , 'Mnbaa_tamarji_doctor_page'
	    );
    // }
}

function Mnbaa_tamarji_sub_assistant_page(){
	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_assistants')){
		add_submenu_page( 
	          'tamarji'   //or 'options.php' 
	        , 'assistant' 
	        , __('Assistant','tamarji')
	        , 'Mnbaa_Tamarji_edit_assistants'
	        , 'assistant'
	        , 'Mnbaa_tamarji_assistant_page'
	    );
	// }
}

function Mnbaa_tamarji_sub_patient_page(){
	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_patients')){
		add_submenu_page( 
	          'tamarji'   //or 'options.php' 
	        , 'patient' 
	        , __('Patients','tamarji')
	        , 'Mnbaa_Tamarji_edit_patients'
	        , 'patient'
	        , 'Mnbaa_tamarji_patient_page'
	    );
	// }
}
function Mnbaa_tamarji_patient_file_page(){
	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_view_patients_files')){
		add_submenu_page( 
	          null  //or 'options.php' 
	        , 'patient' 
	        , __('Patient File','tamarji')
	        , 'Mnbaa_Tamarji_view_patients_files'
	        , 'patient_file'
	        , 'Mnbaa_tamarji_patient_file'
	    );
	// }
}
function Mnbaa_tamarji_sub_patient_file(){
	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_patient')){
		add_submenu_page( 
	          'tamarji'  //or 'options.php' 
	        , 'patient' 
	        , __('Patient File','tamarji')
	        , 'Mnbaa_Tamarji_patient'
	        , 'patient_file'
	        , 'Mnbaa_tamarji_patient_file'
	    );
	// }
}
//
 function Mnbaa_tamarji_sub_department(){
 	global $blog_id;
	
	//if(current_user_can_for_blog($blog_id, 'Mnbaa_tamarji_manage_departments')){
		add_submenu_page( 'tamarji', 
		'departments',
		 __('Department','tamarji'),
		 'Mnbaa_tamarji_manage_departments',
		 'edit-tags.php?taxonomy=Department');
	//	}
 	
 }
//
function Mnbaa_tamarji_sub_roles_names(){
 	global $blog_id;
	// if(current_user_can_for_blog($blog_id, 'Mnbaa_tamarji_edit_roles_names')){
		add_submenu_page( 
	          'tamarji'  //or 'options.php' 
	        , 'roles_names' 
	        , __('Roles Name','tamarji')
	        , 'Mnbaa_tamarji_edit_roles_names'
	        , 'roles_names'
	        , 'Mnbaa_tamarji_roles_names_page'
	    );
	// }
}
// integrate with billing menu page
function Mnbaa_tamarji_integrate_with_billing(){
 	global $blog_id;
	if( is_plugin_active('billing/billing.php') ){
		add_submenu_page( 
	          'tamarji'  //or 'options.php' 
	        , 'integrate_with_billing' 
	        , __('Integrate with billing','tamarji')
	        , 'manage_options'
	        , 'integrate_with_billing'
	        , 'Mnbaa_tamarji_integrate_with_billing_page'
	    );
	}
}
 
//doctor page
function Mnbaa_tamarji_doctor_page(){
	include( plugin_dir_path( __DIR__ ) . 'controllers/doctor.php');
}
//assistant page
function Mnbaa_tamarji_assistant_page(){
	include( plugin_dir_path( __DIR__ ) . 'controllers/assistant.php');
}
//patient page
function Mnbaa_tamarji_patient_page(){
	include( plugin_dir_path( __DIR__ ) . 'controllers/patient.php');
}
//roles names page
function Mnbaa_tamarji_roles_names_page(){
	include( plugin_dir_path( __DIR__ ) . 'controllers/roles_names.php');
}

// integrate with billing controller
function Mnbaa_tamarji_integrate_with_billing_page(){
	include( plugin_dir_path( __DIR__ ) . 'controllers/integrate_with_billing.php');
}

function Mnbaa_tamarji_patient_file(){
	include( plugin_dir_path( __DIR__ ) . 'views/patient_file.php');
}

function post_types_add_meta_box() {
	global $custom_posts;
	foreach ( $custom_posts as $post_type => $useless ) {
	    add_meta_box('custom_meta_box', __('Add','tamarji') . ' ' .__(str_replace('_', ' ', $post_type),'tamarji'). ' ' . __(' Info ', 'tamarji'), $post_type.'_metabox_callback', $post_type, 'normal');
	}
}

function reservation_type_metabox_callback() {
	wp_nonce_field( 'Mnbaa_tamarji_metabox', 'Mnbaa_tamarji_metabox_nonce' );
	include (plugin_dir_path( __DIR__ ) . 'views/reservation_type.php');
}

function reservation_metabox_callback() {
	wp_nonce_field( 'Mnbaa_tamarji_metabox', 'Mnbaa_tamarji_metabox_nonce' );
	include (plugin_dir_path( __DIR__ ) . 'views/reservation.php');
}

function visit_metabox_callback() {
	wp_nonce_field( 'Mnbaa_tamarji_metabox', 'Mnbaa_tamarji_metabox_nonce' );
	include (plugin_dir_path( __DIR__ ) . 'views/visit.php');
}

function tamarji_intro(){
	global $blog_id;
	if(current_user_can_for_blog($blog_id,'Mnbaa_Tamarji_doctor'))
		include (plugin_dir_path( __DIR__ ) . 'views/doctor_dashboard.php');
	elseif (current_user_can_for_blog($blog_id,'Mnbaa_Tamarji_assistant'))
		include (plugin_dir_path( __DIR__ ) . 'views/assistant_dashboard.php');
}

function post_types_save_meta_box_data($post_id) {
	global $custom_posts, $Mnbaa_medical_analysis_custom_posts, $blog_id;

	// Check if our nonce is set.
	if ( ! isset( $_POST['Mnbaa_tamarji_metabox_nonce'] ))
		return $post_id;

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['Mnbaa_tamarji_metabox_nonce'], 'Mnbaa_tamarji_metabox' ))
		return $post_id;
	
    //check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

	if ( is_plugin_active('medical-analysis/medical-analysis.php') ) {
		$custom_posts =	array_merge($custom_posts, $Mnbaa_medical_analysis_custom_posts);
	}
	
    // loop through fields and save the data
    foreach ($custom_posts as $post_type => $meta_fields) {
		
		// Delete all related posts
		if ($_POST['post']['delete']) {
			foreach ($_POST['post']['delete'] as $post_to_delete) {
				wp_delete_post( $post_to_delete, 1 );
			}
		}

    	if ($_POST['post_type'] == $post_type) {
    		// check permissions
	        if (!current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_'.$post_type))
	            return $post_id;
			
    		foreach ($meta_fields as $meta_key => $args) {

				switch ($args['save_as']) {
					case 'single':
						$meta_value = trim($_POST['post'][$post_type][$args['name']]);
						if( $meta_value != '' || !empty($meta_value) || $meta_value==0){
							if($args['action']!='add')
								update_post_meta( $post_id, $meta_key, $meta_value );
							else
								add_post_meta( $post_id, $meta_key, $meta_value );
								}
						break;

					case 'array':
						$meta_values = $_POST['post'][$post_type][$args['name']];
						delete_post_meta($post_id, $meta_key);
						if (is_array($meta_values) && !empty($meta_values) )
							foreach ( $meta_values as $meta_value )
								if(trim($meta_value) != '')
									add_post_meta($post_id, $meta_key, $meta_value, false);
						break;
						
					case 'multi':
						$meta_values = $_POST['post'][$post_type][$args['name']];
						delete_post_meta($post_id, $meta_key);
						if (is_array($meta_values) && !empty($meta_values) ){
							foreach ( $meta_values as $meta_value ){
								if (is_array($meta_value) && !empty($meta_value) && strlen(implode($meta_value)) != 0)
									add_post_meta($post_id, $meta_key, serialize($meta_value), false);
							}
						}
						break;

					default:
						
						break;
				}
			}

			if ( $post_type == 'reservation_type' && get_option('integrated_with_billing') ) {
				update_post_meta($post_id, 'billing_category_id', add_billing_subcat($_POST['post'][$post_type]['type_name']));
			}

			if ( $post_type == 'reservation' && get_option('integrated_with_billing') ) {
				update_post_meta($post_id, 'billing_id', add_billing($_POST['post'][$post_type], $post_id));
			}

    	} elseif ( array_key_exists($post_type, $_POST['post']) && is_array($_POST['post'][$post_type]) && !empty($_POST['post'][$post_type]) && strlen(implode($_POST['post'][$post_type])) != 0 ) {
    		
    		remove_action( 'save_post', 'post_types_save_meta_box_data' );
			
    		// check permissions
	        if (!current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_edit_'.$post_type) && !current_user_can_for_blog($blog_id, 'Mnbaa_medical_analysis_edit_'.$post_type))
	            return $new_post_id;
			
			// Create post object
			$new_post = array(
			  'post_type'    => $post_type,
			  'post_status'   => 'publish',
			);
			foreach ($_POST['post'][$post_type] as $k => $value) {

				// Insert the post into the database
				$new_post_id = wp_insert_post( $new_post );
				update_post_meta( $new_post_id, 'parent_id', $post_id );
				
	    		foreach ($meta_fields as $meta_key => $args) {

					switch ($args['save_as']) {
						case 'single':
							$meta_value = trim($_POST['post'][$post_type][$k][$args['name']]);
							if( $meta_value != '' || !empty($meta_value) || $meta_value==0){
								if($args['action']!='add')
									update_post_meta( $new_post_id, $meta_key, $meta_value );
								else
									add_post_meta( $new_post_id, $meta_key, $meta_value );
									}
							break;
	
						case 'array':
							$meta_values = $_POST['post'][$post_type][$k][$args['name']];
							delete_post_meta($new_post_id, $meta_key);
							if (is_array($meta_values) && !empty($meta_values) )
								foreach ( $meta_values as $meta_value )
									if(trim($meta_value) != '')
										add_post_meta($new_post_id, $meta_key, $meta_value, false);
							break;
							
						case 'multi':
							$meta_values = $_POST['post'][$post_type][$k][$args['name']];
							delete_post_meta($new_post_id, $meta_key);
							if (is_array($meta_values) && !empty($meta_values) ){
								foreach ( $meta_values as $meta_value ){
									if (is_array($meta_value) && !empty($meta_value) && strlen(implode($meta_value)) != 0)
										add_post_meta($new_post_id, $meta_key, serialize($meta_value), false);
								}
							}
							break;
	
						default:
							
							break;
					}
				}
			}
		}
    }
}
// billing integrate functions
function add_billing_subcat($name) {
	$main_category_name = __('Resevations', 'tamarji');
	$cat_id = Billing_Category::find_by_name( $main_category_name )->id;
	$subcategory_args = array(
			'parent_id' 	=> $cat_id,
			'name' 			=> $type->type_name,
			'type' 			=> 'in',
			'blog_id' 		=> get_current_blog_id(),
			'active_status' => 0
		);
		
	$object = new Billing_Category();
	return $object -> create($subcategory_args);
}
// billing integrate functions
function add_billing($post, $post_id) {
	if ($post['paid']) {
		$type_name = get_post_meta($post['type'], 'type_name', true);
		$subcat_id = Billing_Category::find_by_name( $type_name )->id;
		$billing_args = array(
				'category_id' 	=> $subcat_id,
				'title' 		=> get_userdata($post['patient_id'])->display_name,
				'value' 		=> $post['paid'],
				'dateTime' 		=> date('Y-m-d H:i:s'),
				'blog_id' 		=> get_current_blog_id(),
				'active_status' => 0,
				'parent_id' 	=> 0
			);
			
		$object = new Billing();
		$reservation = get_post($post_id);
		if ($reservation->billing_id) {
			$billing_args['parent_id'] = $reservation->billing_id;
			$object -> create($billing_args);
			return $reservation->billing_id;
		} else {
			$x = $object -> create($billing_args);
			return $x;
		}
	}
}



// filter reservations for patient to show owned resevations only
function filter_reservations_by_roles( $query ) {
	global $blog_id;
	if ( !current_user_can_for_blog($blog_id, 'manage_options') ) {
		if ( current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_doctor') ) {
			if( strpos($_SERVER["REQUEST_URI"], 'edit.php?post_type=reservation') ){
				$department_id = get_user_meta(get_current_user_id(), 'department_id', true);
				if ($query->query_vars['post_type'] == 'reservation') {
					$query->set( 'meta_query', array(
						        array(
						            'key' => 'department_id',
						            'value' => $department_id,
						        ),
						        array(
						         	'key' => 'doctor_id',
						            'value' => array('', null, 0, get_current_user_id() ),
						            'compare' => 'IN',
						        ),
						        array(
								    'key' => 'confirmedOn',
								    'value' => '',
								    'compare' => '!=',
								),
								array(
								    'key' 		=> 'cancelledOn',
								    'value' 	=> '',
								)
						  ));
				}
			}
		} elseif( current_user_can_for_blog($blog_id, 'Mnbaa_Tamarji_patient') ) {
			if ($query->query_vars['post_type'] == 'reservation') {
		        $query->set( 'post_type', 'reservation' );
		        $query->set( 'meta_key', 'patient_id' );
		        $query->set( 'meta_value', get_current_user_id() );
		        unset($query->query_vars['author']);
			}
		}
	}
}
add_action( 'pre_get_posts', 'filter_reservations_by_roles' );


function Mnbaa_tamarji_admin_init() {
	 /* Register our stylesheet. */
	//load styles
	wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
	wp_enqueue_style('tamarji_style', plugins_url('', __DIR__) . '/views/tamarji_style/tamarji_style.css');
	
	
	//load jquery-ui date picker
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jqueryui-datepicker', plugins_url('', __DIR__) . '/views/js/jqueryui-datepicker.js');
	wp_enqueue_script('tabs', plugins_url('', __DIR__) . '/views/js/tabs.js', array( 'jquery-ui-tabs' ));
	wp_enqueue_script('validation', plugins_url('', __DIR__) . '/views/js/validation.js', array());
	//popup window
	wp_enqueue_script('jquery-ui', plugins_url('', __DIR__) . '/views/js/jquery-ui.js');
	
	wp_enqueue_script('autocomplete', plugins_url('', __DIR__) . '/views/js/autocomplete.js', array( 'jquery-ui-autocomplete' ));
	// 
	// //load ajax files
	wp_register_script("ajax-tamarji",plugins_url('', __DIR__) .'/views/js/ajax-tamarji.js', array('jquery'));
	wp_localize_script('ajax-tamarji', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_enqueue_script('ajax-tamarji');
	
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function Mnbaa_tamarji_textdomain() {
	//load text domain file for translating
	load_plugin_textdomain('tamarji', false, dirname(plugin_basename(__DIR__)) . '/languages/');
	// load_plugin_textdomain( 'my-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' ); 
}
?>
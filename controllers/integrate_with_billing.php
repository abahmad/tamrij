<?php
$object = new Billing_Category();

$main_category_name = __('Resevations', 'tamarji');

// Add category
$category_args = array('name' => $main_category_name, 'type' => 'in', 'blog_id' => get_current_blog_id(), 'active_status' => 0 );
$cat_id = $object -> create($category_args);

$args = array(
	'post_type' => 'reservation_type',
);
$query = new WP_Query( $args );

// Get the results
$types = $query->get_posts();

// check to see if we have users
if (!empty($types)) {
	foreach ($types as $type) {
		// Add subcategory
		$subcategory_args = array('parent_id' => $cat_id, 'name' => $type->type_name, 'type' => 'in', 'blog_id' => get_current_blog_id(), 'active_status' => 0 );
		$subcat_id = $object -> create($subcategory_args);
		update_post_meta($type->ID, 'billing_category_id', $subcat_id);
	}
}

add_option('integrated_with_billing', true);
?>
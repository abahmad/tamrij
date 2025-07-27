<?php
function Mnbaa_Tamarji_label($text = '', $for = '') {
	echo '<label for="'.$for.'">'.$text.'</label>';
}

function Mnbaa_Tamarji_input($field, $value = '', $class = '') {
	echo '<input type="text" name="'.$field.'" id="'.$field.'_ID" value="'.$value.'" size="40" class="'.$class.'" />';
}
function Mnbaa_Tamarji_input_read_only($field, $value = '') {
	echo '<input type="text" name="'.$field.'" id="'.$field.'_ID" value="'.$value.'" size="40" readonly />';
}

function Mnbaa_Tamarji_textarea($field, $value = '') {
	echo '<textarea name="'.$field.'" id="'.$field.'_ID" cols="60" rows="4">'.$value.'</textarea>';
}

function Mnbaa_Tamarji_img_input($field, $post_seo_meta = '') {
	$og_image = plugins_url('mnbaa_seo/images','').'/noimage.jpg';
	if ($post_seo_meta) { $og_image = wp_get_attachment_image_src($post_seo_meta, 'medium'); $og_image = $og_image[0];}
	echo '<input name="'.$field['name'].'" type="hidden" class="custom_upload_image" value="'.$post_seo_meta.'" />
		<img src="'.$og_image.'" class="custom_preview_image" alt="" /><br />
		<input class="custom_upload_image_button button" type="button" value="Choose Image" />
		<small> <a href="#" class="custom_clear_image_button">Remove Image</a></small><br clear="all" />
		<span class="description">'.$field['desc'].'</span><br />';
}

function Mnbaa_Tamarji_select($field = '', $option = array(), $value = '') {
	echo '<select name="'.$field.'" id="'.$field.'_ID">';
	foreach ($options as $option) {
		echo '<option', $value == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select>';
}
// function to make multi select control
function Mnbaa_Tamarji_multi_select($field = '', $post_seo_meta = '') {
	echo '<select name="'.$field['name'].'" id="'.$field['name'].'_ID" multiple>';
	foreach ($field['options'] as $option) {
		echo '<option', $post_seo_meta == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';
}

function Mnbaa_Tamarji_radio($field, $label,$value = '') {
	echo '<input type="radio" name="'.$field.'" id="'.$field.'_ID"  value="'.$value.'" >'.$label.'';
}

function Mnbaa_Tamarji_password($field, $value = '') {
	echo '<input type="password" name="'.$field.'" id="'.$field.'_ID" value="'.$value.'" size="40" />';
}
function Mnbaa_Tamarji_url($field, $value = '', $class = '') {
	echo '<input type="url" name="'.$field.'" id="'.$field.'_ID" value="'.$value.'" size="40" class="'.$class.'" />';
}
function Mnbaa_Tamarji_mail($field, $value = '', $class = '') {
	echo '<input type="email" name="'.$field.'" id="'.$field.'_ID" value="'.$value.'" size="40" class="'.$class.'" />';
}


function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}
include_once 'mnbaa_wp_shortcuts.php';
?>

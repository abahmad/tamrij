<?php
/**
 * DB main class
 * contains all DB functions
 */

class Mnbaa_Tamergy_DB {

	protected static $table_name;
	protected static $active_status_condition;
	protected static $db_fields;

	function __construct() {
	}

	public static function find_all() {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE blog_id = " . get_current_blog_id() . " AND " . static::$active_status_condition);
	}

	public static function find_limited($limit, $offset) {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE blog_id = " . get_current_blog_id() . " AND " . static::$active_status_condition . " ORDER by id DESC LIMIT " . $limit . " OFFSET " . $offset);
	}

	public static function find_last_id() {
		global $wpdb;
		$obj = $wpdb -> get_row("SELECT id FROM " . static::$table_name . " ORDER BY id DESC LIMIT 1");
		return $obj -> id;
	}

	public static function find_by_id($id) {
		global $wpdb;
		return $wpdb -> get_row("SELECT * FROM " . static::$table_name . " WHERE id=" . $id . " AND " . static::$active_status_condition);
		//echo $wpdb->last_query;
	}

	public static function find_by_parent_id($id) {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE parent_id=" . $id . " AND blog_id = " . get_current_blog_id() . " AND " . static::$active_status_condition);
	}

	public static function find_by_field($field, $value) {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE " . $field . "=" . $value . " AND blog_id = " . get_current_blog_id() . " AND " . static::$active_status_condition);

	}

	public static function find_where($where) {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE " . $where . " AND blog_id = " . get_current_blog_id() . " AND " . static::$active_status_condition);
	}

	public function create($post) {
		global $wpdb;
		$result = $wpdb -> insert(static::$table_name, $post);
		// echo $wpdb->last_query.'<br>';
		return $result;

	}

	public function update($post, $id) {
		global $wpdb;
		$result = $wpdb -> update(static::$table_name, $post, array('id' => $id));
		return $result;
	}

	public function delete($id) {
		global $wpdb;
		$result = $wpdb -> query('DELETE FROM ' . static::$table_name . '
			   WHERE id = ' . $id);
		return $result;

	}

	//return with last inserted id
	public function insert($post) {

		global $wpdb;
		$result = $wpdb -> insert(static::$table_name, $post);

		//echo $wpdb->last_query;
		$lastid = $wpdb -> insert_id;
		return $lastid;

	}

	public static function find_by_id_noblogid($id) {
		global $wpdb;
		return $wpdb -> get_row("SELECT * FROM " . static::$table_name . " WHERE id=" . $id . " AND " . static::$active_status_condition);

	}

	public static function find_all_noblogID() {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE  " . static::$active_status_condition);
	}

	public static function find_by_field_noblogID($field, $value) {
		global $wpdb;
		return $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE " . $field . "=" . $value . " AND " . static::$active_status_condition);

	}
	
	public static function find_distinict($field) {
		global $wpdb;
		return $wpdb -> get_results("SELECT DISTINCT ".$field." FROM " . static::$table_name . " WHERE ". static::$active_status_condition);
//echo $wpdb->last_query;
	}
	public static function find_by_field_order_by($field,$value,$order) {
		global $wpdb;
		return  $wpdb -> get_results("SELECT * FROM " . static::$table_name . " WHERE " . $field . "=" . $value . " AND " . static::$active_status_condition ." order by ".$order);
//echo $wpdb->last_query;
	}
	

}
?>
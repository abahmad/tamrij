<?php 

class Mnbaa_Tamergy_Patient extends Mnbaa_Tamergy_DB {
	
	protected static $table_name = 'patient';
	protected static $active_status_condition = 'active_status=1 ';
	
	
	public static function login($where) {
		
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM " . static::$table_name . " WHERE " . $where  . " AND " .static::$active_status_condition ."LIMIT 1");
		
	}
	
}
?>
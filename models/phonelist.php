<?php 

class Mnbaa_Tamergy_Phonelist extends Mnbaa_Tamergy_DB {
	
	protected static $table_name = 'phonelist';
	protected static $active_status_condition = 'patient_id IN (SELECT id FROM patient WHERE active_status=1)';
	
	public static function phone_create($post,$last_id) {
		//var_dump($post);
		global $wpdb;
		$result = sizeof($post['Number']);
		
		for ($i = 0; $i < $result; $i++) {
			$wpdb->insert( static::$table_name, array( 'patient_id' => $last_id,'prefix' => $post['prefix'][$i], 'number' => $post['number'][$i] , 	'type' => $post['type'][$i] ));
		}
		
		
		//echo $wpdb->last_query;
		//return $result;

	}
	
	public function update_by_parent_id($post, $id) {
		global $wpdb;
		$result = $wpdb->update( 
			static::$table_name, 
			$post, 
			array( 'patient_id' => $id )
		);
		return $result;
	}
	
	public static function find_by_patient_id($id) {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM " . static::$table_name . " WHERE patient_id=" . $id . " AND " .static::$active_status_condition );
	}
	
	public static function phone_update($post,$patient_id) {
		//var_dump($post);
		global $wpdb;
		$result = $wpdb->query(
			'DELETE FROM ' .static::$table_name . '
			   WHERE patient_id = '.$patient_id
		);
		$result = sizeof($post['number']);
		
		for ($i = 0; $i < $result; $i++) {
			$wpdb->insert( static::$table_name, array( 'patient_id' => $patient_id,'prefix' => $post['prefix'][$i], 'number' => $post['number'][$i] , 	'type' => $post['type'][$i] ));
		}
		

	}
	
}
?>
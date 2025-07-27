<?php 

class Mnbaa_Tamergy_Reservation extends Mnbaa_Tamergy_DB {
	
	protected static $table_name = 'reservation';
	protected static $active_status_condition = 'active_status=1';
	
}
?>
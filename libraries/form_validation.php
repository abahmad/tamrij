<?php 
 
class Mnbaa_Tamarji_Form_Validation {
	
	 public $error_messages;
	 private $error_list;
	 public $fields;
	
	function  __construct() {
		
		$this->error_messages = array(
            'required' => __('The field is required','tamergy'),
            'valid_email' => __('Email is not in correct format','tamergy'),
            'is_numeric_no_firstzero' => __('must contain numbers with no zero in first digit','tamergy'),
            'exact_length' => __('must contain  14 digits','tamergy'),
			'matches' => __('Password and Password confirm not matced','tamergy'),
			'is_unique_input' => __('The field must be unique','tamergy'),
         
        );
		//$this->fields = $_POST['atributes'];
	}
	
	function validate($field_rules)
    {
        //Validate each of the fields
		//var_dump($field_rules);
            foreach ($field_rules as $label_field => $array)
            {
				
                $rules = explode('|', $array[0]);
				
                foreach ($rules as $rule)
                {
                    $result = null;
					
                    if (isset($array[1]))
                    {
						
                        // Call the function that corresponds to the rule
						//echo $rule;
						if(strpos($rule,"*")) {
							$arr_rule=explode("*",$rule);
							//var_dump($arr_rule);
							$rule=$arr_rule[0];
							switch($rule){
								case "is_unique_input":
									if($arr_rule[4]=='') $arr_rule[4]=0;
									$result = $this->$rule($arr_rule[1],$arr_rule[2],$arr_rule[3],$arr_rule[4],$arr_rule[5]);
								break;
								default:
									
									$result = $this->$rule($array[1],$arr_rule[1]);
								break;
							}
							
						}else{
							if (!empty($rule)){
								
							   $result = $this->$rule($array[1]);
							   //echo $result;
							   
							}
						}
						
                        // Handle errors
                        if ($result === false)
                            $this->set_error($label_field, $rule);
                    }
                }
            }
            if (empty($this->error_list))
            {
                return TRUE;
            }
            else
            {
                 return $this->error_list;
            }
        
    }
	
	function set_error($field, $rule)
    {
        $this->error_list .= "<div class='error'>$field: " . $this->error_messages[$rule] . "</div>";
    }
	
	public static function required($str)
	{
		
		if ( ! is_array($str))
		{
			
			return (trim($str) == '') ? FALSE : TRUE;
		}
		else
		{

			foreach($str as $item){
				if(trim($item) == ''){
					$val=FALSE;
					break;
					
				}else
					$val=TRUE;	
			}
			
		}
		return $val;
	}
	public static function numeric($str)
	{
		if (filter_var($str, FILTER_VALIDATE_INT) !== FALSE) {
		 return TRUE;
		}
	}
	
	public static function exact_length($str, $val)
	{

		if (function_exists('mb_strlen'))
		{
			return (mb_strlen($str) != $val) ? FALSE : TRUE;
		}

		return (strlen($str) != $val) ? FALSE : TRUE;
	}
	
	public static function is_numeric_no_firstzero($str)
	{
		if ( ! preg_match( '/^[0-9]+$/', $str))
		{
			return FALSE;
		}
		$str[0]=substr($str, 0, 1);
		if ($str[0] == 0)
		{
			return FALSE;
		}

		return TRUE;
	}
	
	public static function matches($str, $field)
	{
		if ( ! isset($field))
		{
			return FALSE;
		}

		return ($str !== $field) ? FALSE : TRUE;
	}
	
	public static function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}
	
	
	public static function is_unique_input($table_name,$field, $value,$id_value,$id_field)
	{	
		
		global $wpdb;
		if($id_value !='0')
			$result=$wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE " . $field . " = " . "'".$value ."'". " AND ".$id_field ." != " . $id_value);
		else
			$result=$wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE " . $field . " = " . "'".$value."'" );
		//echo $wpdb->last_query;
		//$result_no=sizeof($result);
		if(sizeof($result)!=0)
			return FALSE;
	}
	
	
	/*public static function required($str)
	{
		return (trim($str) == '') ? FALSE : TRUE;
	}*/

}

?>
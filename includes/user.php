<?php  

	// If it's going to need the database, then it's
	// probably smart to require it before we start.
	require_once(LIB_PATH.DS.'database.php');

	class User extends DatabaseObject{
		
		public $id;
		public $username;
		public $password;
		public $first_name;
		public $last_name;

		function __construct(){
			self::$table_name = 'users';
			self::set_db_fields_array();
		}

		public function full_name(){
			if(isset($this->first_name) && isset($this->last_name)){
				return $this->first_name." ".$this->last_name;
			}
			else{ return ""; }
		}

		public static function authenticate($username="",$password=""){
			global $database;
			$username = $database->escape_value($username);
			$password = $database->escape_value($password);

			$sql  = " SELECT * FROM users ";
			$sql .= " WHERE username='".$username;
			$sql .= "' AND password='".$password;
			$sql .= "' LIMIT 1";

			$result_array = self::find_by_sql($sql);
			return !empty($result_array) ? array_shift($result_array) : false;
		}
	}
	new User();
?>
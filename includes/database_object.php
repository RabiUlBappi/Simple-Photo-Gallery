<?php  

	// If it's going to need the database, then it's
	// probably smart to require it before we start.
	require_once(LIB_PATH.DS.'database.php');

	class DatabaseObject{
		protected static $table_name;
		protected static $db_fields = array();

		// function __construct(){
		// 	self::$db_fields = self::get_db_table_fields(self::$table_name);
		// }

		protected function set_db_fields_array(){
			self::$db_fields = self::get_db_table_fields(self::$table_name);
		}
		
		// common database methods
		public static function find_all(){
			return self::find_by_sql("SELECT * FROM ".static::$table_name);
		}

		public static function find_by_id($id=0){
			global $database;
			$result_array = self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
		}

		public static function find_by_sql($sql=""){
			global $database;
			$result_set = $database->query($sql);
			$object_array = array();
			while ($row = $database->fetch_array($result_set)) {
				$object_array[] = self::instantiate($row);
			}
			return $object_array;
		}

		public static function count_all(){
			global $database;
			$sql = "SELECT COUNT(*) FROM ".static::$table_name;
			$result_set = $database->query($sql);
			$row = $database->fetch_array($result_set);
			return array_shift($row);
		}

		public static function instantiate($record){
			$class_name = get_called_class();
			$object = new $class_name;

			// simple long form
			// $object->id         = $record['id'];
			// $object->username   = $record['username'];
			// $object->password   = $record['password'];
			// $object->first_name = $record['first_name'];
			// $object->last_name  = $record['last_name'];

			// more dynamic short form
			foreach ($record as $attribute => $value) {
				if($object->has_attribute($attribute)){
					$object->$attribute = $value;
				}
			}
			return $object;
   		}

		private function has_attribute($attribute){
			$object_vars = $this->attributes();
			return array_key_exists($attribute, $object_vars);
		}

		protected function attributes(){
			$attributes = array();
			foreach (self::$db_fields as $field) {
				if(property_exists($this, $field)){
					$attributes[$field] = $this->$field;
				}
			}
			return $attributes;
		}

		protected function sanitized_attributes(){
			global $database;
			$clean_attributes = array();
			foreach ($this->attributes() as $key => $value) {
				$clean_attributes[$key] = $database->escape_value($value);
			}
			return $clean_attributes;
		}

		public function save(){
			return isset($this->id) ? $this->update() : $this->create();
		}

		protected function create(){
			global $database;
			$attributes = $this->sanitized_attributes();
			$sql  = "INSERT INTO ".self::$table_name." (";
			$sql .= join(", ", array_keys($attributes));
			$sql .= " ) VALUES ( '";
			$sql .= join("', '", array_values($attributes));
			$sql .= "')";

			if($database->query($sql)){
				$this->id = $database->insert_id();
				return true;
			}
			else{ return false; }
		}

		protected function update(){
			global $database;
			$attributes = $this->sanitized_attributes();
			$attribute_pairs = array();
			foreach ($attributes as $key => $value) {
				$attribute_pairs[] = $key."='".$value."'";
			}
			$sql  = "UPDATE ".self::$table_name." SET";
			$sql .= join(", ", $attribute_pairs);
			$sql .= "WHERE id='".$database->escape_value($this->id);
			$database->query($sql);
			return ($database->affected_rows()==1) ? true : false;
		}

		public function delete(){
			global $database;
			$sql  = "DELETE FROM ".self::$table_name;
			$sql .= " WHERE id=".$database->escape_value($this->id);
			$sql .= " LIMIT 1 ";
			$database->query($sql);
			return ($database->affected_rows()==1) ? false : true; // returning reverse boolean since there's a bug with affected_rows() method
		}

		public static function get_db_table_fields($table_name=""){
			global $database;
			$sql = "SHOW COLUMNS FROM ".$table_name;
			$result = $database->query($sql);
			$fieldnames = array();
	        while ($row = mysqli_fetch_assoc($result)) { 
	           $fieldnames[] = $row['Field']; 
	        } 
	        return $fieldnames;
		}
	}
?>
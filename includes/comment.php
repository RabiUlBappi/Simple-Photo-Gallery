<?php  

	// If it's going to need the database, then it's
	// probably smart to require it before we start.
	require_once(LIB_PATH.DS.'database.php');

	class Comment extends DatabaseObject{
		
		public $id;
		public $photograph_id;
		public $created;
		public $author;
		public $body;

		function __construct(){
			self::$table_name = 'comments';
			self::set_db_fields_array();
		}

		public static function make($photo_id=0,$author="Anonymous", $body=''){
			if(!empty($photo_id) && !empty($author) && !empty($body)){
				$comment = new Comment();
				$comment->photograph_id = (int)$photo_id;
				$comment->created = strftime("%Y-%m-%d %H:%M:%S",time());
				$comment->author = $author;
				$comment->body = $body;
				return $comment;
			}
			else{
				return false;
			}
		}

		public static function find_comments_on($photo_id=0){
			global $database;
			$sql  = "SELECT * FROM ".self::$table_name;
			$sql .= " WHERE photograph_id=".$database->escape_value($photo_id);
			$sql .= " ORDER BY created ASC";
			return self::find_by_sql($sql);
		}

		// To use this method below DOWNLOAD PHPMailer

// 		public function try_to_send_notification(){
// 			$mail = new PHPMailer();

// 			$mail->IsSMTP();
// 			$mail->Host 	= "your.host.com";
// 			$mail->Port 	= 25;
// 			$mail->SMTPAuth = false;
// 			$mail->Username = "your_username";
// 			$mail->Password = "your_password";

// 			$mail->FromName = "Photo Gallery";
// 			$mail->From 	= "someone@something.com";
// 			$mail->AddAddress("other@other.com","Photo Gallery Admin");
// 			$mail->Subject  = "New Photo Gallery Comment";
// 			$created = datetime_to_text($this->created);
// 			$mail->Body 	= <<<EMAILBODY
// A new comment has been received in the Photo Gallery.

// At {$created}, {$this->author} wrote:

// {$this->body}
// EMAILBODY;

			$result = $mail->Send();
			return $result;
		}
	}

	new Comment();
?>
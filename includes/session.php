<?php  

	// A class to help with sessions
	// In our case, primarily to manage logging users in and out 

	// Keep in mind when working with sessions that, it is generally
	// inadvisable to store DB related objects in sessions

	class Session{
		private $logged_in = false;
		public $user_id;
		public $message;

		function __construct(){
			session_start();
			$this->check_message();
			$this->check_login();
			if($this->logged_in){
				// do something
			}
			else{
				// do something else
			}
		}

		public function is_logged_in(){
			return $this->logged_in;
		}

		public function login($user){
			// database should find user based username and password
			if($user){
				$this->user_id=$_SESSION['user_id']=$user->id;
				$this->logged_in = true;
			}
		}

		public function logout(){
			unset($_SESSION['user_id']);
			unset($this->user_id);
			$this->logged_in = false;
		}

		public function message($msg=''){
			if(!empty($msg)){
				// the it is "set message"
				$_SESSION['message'] = $msg;
			}
			else{
				// the it is "get message"
				return $this->message;
			}
		}

		private function check_login(){
			if(isset($_SESSION['user_id'])){
				$this->user_id = $_SESSION['user_id'];
				$this->logged_in = true;
			}
			else{
				unset($this->user_id);
				$this->logged_in = false;
			}
		}

		private function check_message(){
			// is there a message stored in the session
			if(isset($_SESSION['message'])){
				// add it as an attribute and erase the stored version
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			}
			else{
				$this->message = "";
			}
		}
	}

	$session = new Session();
	$message = $session->message();

?>
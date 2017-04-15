<?php
class Session {
	private $LoggedIn = false;
	public $Message;
	public $Id; // UserId
	public $Name; // REGISTRATION - PROFILE?
	public $Source; // REGISTRATION
	public $Agent; // REGISTRATION
	public $IP; // REGISTRATION
	public $Sales; // ADMIN
	public $Admin; // ADMIN
	function __construct() {
		if ( $this->is_logged_in () ==  false) {
		global $lang;
		session_start ();
		$this->IP = $_SESSION ['IP'] = $_SERVER ['REMOTE_ADDR'];
		if (isset ( $_SERVER ['HTTP_REFERER'] )) {
			$this->Source = $_SESSION ['Source'] = $_SERVER ['HTTP_REFERER'];
		} else {
			$this->Source = $_SESSION ['Source'] = "0";
		}
		if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
			$this->Agent = $_SESSION ['Agent'] = $_SERVER ['HTTP_USER_AGENT'];
		} else {
			$this->Agent = $_SESSION ['Agent'] = "0";
		}
		if (isset ( $_SESSION ['Id'] )) {
			if (!(isset($this->is_logged_in)&&( $this->is_logged_in ()))) {
			$User = User::find_by_id ( $_SESSION ['Id'] );
			$this->login ( $User );
				}
		} else if (isset ( $_COOKIE ['Id'] )) {
			if (!(isset($this->is_logged_in)&&( $this->is_logged_in ()))) {
			$User = User::find_by_id ( $_COOKIE ['Id'] );
			$this->login ( $User );
			}
		}
		$this->sessionlog ();
	}}
	public function login($TheUser, $SetCookie = false) {
		// database should find user based on username/password
		if ($TheUser) {
			$this->Id = $_SESSION ['Id'] = $TheUser->Id;
			$this->Name = $_SESSION ['Name'] = $TheName;
			$this->LoggedIn = true;
			if ($SetCookie == true) {
				setcookie ( 'Id', $TheUser->Id, time () + 60 * 60 * 24 * 365, '/' );
				$_COOKIE ['Id'] = $TheUser->Id;
			}
		}
	}
	public function logout() {
		unset ( $_COOKIE ['Id'] );
		setcookie ( 'Id' );
		session_destroy ();
	}
	protected function sessionlog() {
		$theLog = new Log ();
		$Page = @$_REQUEST ['page'];
		$theLog->Page = basename ( $_SERVER ['PHP_SELF'] );
		$theLog->Descrip = "SessionLog"; // $this->Descrip;
		$theLog->Message = $this->Message;
		$theLog->IP = $_SESSION ['IP'];
		$theLog->Agent = $_SESSION ['Agent'];
		$theLog->Source = $_SESSION ['Source'];
		//$theLog->RHost = $this->RHost;
		// $theLog->Host = $this->Host;
		// $theLog->Server = $this->Server;
		if(strlen($theLog->Name)>1){
			$theLog->save ();
		}
	
	}
	public function is_logged_in() {
		return $this->LoggedIn;
	}
	public function is_sales() {
		return $this->Sales;
	}
	public function login_by_codes($A, $B) {
		$TheUser = User::get_record_by_codes ( $A, $B );
		if ($TheUser) {
			if ($TheUser->EmailConfirm == 0) {
				redirect_to ( "registrar.php?{$TheUser->get_record_codes()}" );
			} else {
				$session->login ( $TheUser );
				redirect_to ( "register.php" );
			}
			redirect_to ( "register.php" );
		}
	}
}

$session = new Session ();
?>
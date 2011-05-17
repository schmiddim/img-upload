<?php
class Login{
	private $login=false;
	private $user;
	private $password;
	public function __construct(){
		session_start();
		$this->user="michael";
		$this->password=md5("nutellamitwurst");


	}//__construct

	public function validateLogin($user, $password){
		//escape
		$user=htmlspecialchars($user);
		$pass=htmlspecialchars($password);
		
		//session started?
		if(isset($_SESSION['user']) && isset($_SESSION['password'])){				
			$user=$_SESSION['user'] ;
			$password=$_SESSION['password'] ;
		}			
		if ($user=$this->user && $this->password == md5($password)){
				
			$_SESSION['user'] = $user;
			$_SESSION['password'] = $password;
			$this->login=true;				
		}
	}//validateLogin

	public function logout($logoutVar){
		$logoutVar=htmlspecialchars($logoutVar);
		if (isset($logoutVar) && $logoutVar){
			session_destroy();
			$this->login=false;
		}
	}
	public function loggedIn(){

		return $this->login;
	}
}
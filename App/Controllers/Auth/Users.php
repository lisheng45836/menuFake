<?php
namespace App\Controllers\Auth;
use \Core\View;
use App\Controllers\Auth\Helper;
use App\Models\Auth;

/**
* 
*/

class Users extends \Core\Controller
{
	public $errors = [];
	// protected function before()
	// {
	// 	echo "(before)";
	// 	return false;
	// }

	// protected function after()
	// {
	// 	//echo "after";
	// }

	// public function indexAction()
	// {
	// 	echo 'Users';
	// }

	public function loginAction(){

		//View::renderTemplate('Auth/login.html');
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			View::renderTemplate('Auth/login.html');
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			$email 			= htmlspecialchars($_POST['email']);
			$password		= htmlspecialchars($_POST['password']);
			$remember		= isset($_POST['remember']);

			if(empty($email)){
				$this->errors[] = "Email is empty";
			}
			if(empty($password)){
				$this->errors[] = "password is empty";
			}

			if(!empty($this->errors)){
				foreach ($this->errors as $error) {
					echo Helper::errorHandle($error);	
				}
			}else{
				$dbPassword = Auth::getPassword($email);
				session_start();
				if(md5($password) === $dbPassword){
					if($remember == "on"){
						setcookie('email',$email,time() + 86400);
					}
					$_SESSION['email'] = $email;
	
					Helper::redirect('/');
					
				}else{
					echo Helper::errorHandle("login nah");
				}
			}
		}
	}

	public static function auth(){
		session_start();
		if(isset($_SESSION['email'])|| isset($_COOKIE['email'])){
			return true;
		}else{
			return false;
		}
	}

	public function logOut(){
		$this->auth(); 
		session_destroy();
		unset($_COOKIE['email']);
		setcookie('email','',time()-86400);
		Helper::redirect('/auth/users/login');
	}
}
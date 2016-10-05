<?php
namespace App\Controllers\Auth;
use \Core\View;
use App\Controllers\Auth\Helper;
use App\Models\Auth;
use App\Models\User;

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

		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			if(!$this->auth()){
				View::renderTemplate('Auth/login.html');
			}else{
				Helper::redirect('/');
			}
			
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
				$data = Auth::getUserInfo($email);
				$dbPassword = $data['password'];
				$role = $data['role'];
				session_start();
				if(md5($password) === $dbPassword){ //md5()
					if($remember == "on"){
						setcookie('email',$email,time() + 86400,'/');
					}
					$_SESSION['email'] = $email;
					if($role == "1"){
						Helper::redirect('/');
					}
					if($role == "2"){
						Helper::redirect('/partner'); // issue penel.
					}
					if($role == "3"){
						Helper::redirect('/admin');
					}
					
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

	public static function getUser(){
		
			$email = $_SESSION['email'];
			return Auth::getUserInfo($email);
		
	}

	public function logOut(){
		session_start();
		session_destroy();
		if(isset($_COOKIE['email'])){
			unset($_COOKIE['email']);
			setcookie('email','',time() - 86400);
		}
		Helper::redirect('/auth/users/login');
	}

	public function forgot(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			session_start();
			$token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));
			View::renderTemplate('Auth/forgot.html',['token'=>$token]);
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			session_start();
			if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']){
				$email = htmlspecialchars($_POST['email']);
				if(User::emailExists($email)){
					$validationCode = md5($email + microtime());
					setcookie('tempCode', $validationCode, time()+ 900,'/');
					Auth::updateValidation($validationCode,$email);
					// send email
					$subject = "Please reset your password!";
					$msg = "password rest code [$validationCode]
					Click the link to reset yourt password: http://localhost:8888//auth/users/validation?email=$email&validationCode=$validationCode";
					$headers = "From: jinfang1969@gmail.com";
					Helper::sendMail($email,$subject,$msg,$headers); //
					Helper::redirect('/auth/users/login');
				}else{
					echo "email not exists";
				}
			}else{echo "token not match"; }

		}
	}

	public function validation(){
		if(isset($_COOKIE['tempCode'])){
			if(!isset($_GET['email']) && !isset($_GET['validationCode']) || empty($_GET['email']) && empty($_GET['validationCode'])){
				Helper::redirect('/auth/users/login');
			}else{
				if(isset($_GET['validationCode'])){
					$email = htmlspecialchars($_GET['email']);
					$validationCode = htmlspecialchars($_GET['validationCode']);
					if(User::findUser($email,$validationCode)){
						setcookie('tempCode',$validationCode,time() + 900,'/');
						Helper::redirect("/auth/users/reset?email=$email&validationCode=$validationCode");
					}else{echo "dskl";}
				}else{echo"sddd";}
			}

		}else{
			echo "no";
		}
	}

	public function reset(){

		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			session_start();
			$token = $_SESSION['token'];
			$email = htmlspecialchars($_GET['email']);
			View::renderTemplate('Auth/reset.html',['token'=>$token,'email'=>$email]);
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			session_start();
			if(isset($_SESSION['token']) && isset($_POST['token'])){
				if($_SESSION['token'] === $_POST['token']){
					if($_POST['password'] === $_POST['repassword']){
						$newPassword = htmlspecialchars($_POST['password']);
						$email = htmlspecialchars($_POST['email']);
						Auth::updatePassword($newPassword,$email);
						Helper::redirect('/auth/users/login');
					}else{
						echo "password not match";
					}
				}else{
					echo "not the same";
					}
			}else{echo "na";}

			var_dump($_SESSION['token']);
			var_dump($_POST['token']);
		}

	}
}
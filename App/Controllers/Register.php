<?php
namespace App\Controllers;
use \Core\View;
use App\Models\User;

/**
* 
*/
class Register extends \Core\Controller
{
	public $min = 3;
	public $errors = [];
	// 

	// public function index(){

	// 	View::renderTemplate('Login/registration.html');
	// }
	public function registration(){

		View::renderTemplate('Login/registration.html');

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){

			$firstName 			= htmlspecialchars($_POST['firstName']);
			$lastName 			= htmlspecialchars($_POST['lastName']);
			$userName 			= htmlspecialchars($_POST['userName']);
			$email 				= htmlspecialchars($_POST['email']);
			$address			= htmlspecialchars($_POST['address']);
			$password			= htmlspecialchars($_POST['password']);
			$confirmPassword	= htmlspecialchars($_POST['confirmPassword']);

			if($this->inputValidation($firstName,$lastName,$userName,$email,$address,$password,$confirmPassword)){

				$validationCode = User::register($firstName,$lastName,$userName,$email,$password,$address);

				$subject = "Activate Account";
				$msg = " Please click the link below to activate your Account
				http://localhost:8888//register/activateUser?email=$email&validationCode=$validationCode
				";


				$headers = "From: jinfang1969@gmail.com";

				$this->sendMail($email, $subject, $msg, $headers);
				//return header("Location: /");

			}else{
				//echo "fuck";
			}

		}
	}

	public function inputValidation($firstName,$lastName,$userName,$email,$address,$password,$confirmPassword){
		if(strlen($firstName) < 3 ){
			$this->errors[] = "Your first name must be lager then 3 characters";
		}

		if(strlen($lastName) < 3 ){
			$this->errors[] = "Your last name must be lager then 3 characters";
		}

		if(strlen($userName) < 3 ){
			$this->errors[] = "Your user name must be lager then 3 characters";
		}

		if(strlen($userName) == 0){
			$this->errors[] = "Please enter your address";
		}

		if(User::userExists($userName)){
			$this->errors[] = "Sorry this user name ($userName) is already registered. Please choose other one.";
		}

		if(User::emailExists($email)){
		 	$this->errors[] ="Sorry this Email: $email is already registered";
		 }

		if($password !== $confirmPassword){
			$this->errors[] ="Password not match";
		}

		if(!empty($this->errors)){

			foreach ($this->errors as $error) {
				echo $this->errorHandle($error);
			}
			//return false;

		}else{

			return true;
			
			}
	}


	protected function sendMail($email, $subject, $msg, $headers){
		return mail($email, $subject, $msg, $headers);
	}

	public function activateUser(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$email 			= htmlspecialchars($_GET['email']);
			$validationCode = htmlspecialchars($_GET['validationCode']);

			if(User::findUser($email,$validationCode)){ // Check if user exists 
				User::activate($email,$validationCode);
				$msg = "<p class='bg-success'>Your account has been activated please login</p>";
				//View::renderTemplate('Login/activate.html',['msg' => $msg]);
				echo $msg;
			}else{
				$msg = "<p class='bg-success'>Your account cannot be activate</p>";
				//View::renderTemplate('Login/activate.html',['msg' => $msg]);
				echo $msg;
			}
		}
	}

	// Handle Error message
	protected function errorHandle($error){
		$error= <<<HTML
	<div class="alert alert-danger alert-dismissible" role="alert">
	  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	<strong>Warning!</strong> $error
	 </div>
HTML;
		return $error;
	}



}	
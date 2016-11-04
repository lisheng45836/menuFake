<?php
/****************************************************/
// Filename: Register.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use App\Controllers\Auth\Helper;
use \Core\View;
use App\Models\User;
use App\Controllers\Auth\Users;

/**
* Register controller
* Handler Register process
*/
class Register extends \Core\Controller
{
	public $min = 3;		// min 3 input characters
	public $errors = []; 	// error array
	
	/**
	 * @des registration function, create new recore in database.
	 * Handler two type uesrs: customer and business partner
	 */
	public function registration()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){	//check if is customer or partner
			if(isset($_GET['customer'])){
				$auth = Users::auth();
				View::renderTemplate('Auth/registration.html',['auth'=>$auth]);
			}
			if(isset($_GET['partner'])){
				$auth = Users::auth();
				View::renderTemplate('Auth/registPartner.html',['auth'=>$auth]);
			}
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			$firstName 			= htmlspecialchars($_POST['firstName']);
			$lastName 			= htmlspecialchars($_POST['lastName']);
			$userName 			= htmlspecialchars($_POST['userName']);
			$email 				= htmlspecialchars($_POST['email']);
			$address			= htmlspecialchars($_POST['address']);
			$role				= htmlspecialchars($_POST['role']);
			$password			= htmlspecialchars($_POST['password']);
			$confirmPassword	= htmlspecialchars($_POST['confirmPassword']);

			// check inputValidation if is true
			if($this->inputValidation($firstName,$lastName,$userName,$email,$address,$password,$confirmPassword)){

				$validationCode = User::register($firstName,$lastName,$userName,$email,$password,$address,$role);

				if($role === "1"){ // if $role == 1(customer), send activate link msg to user email
					$subject = "Activate Account";
					$msg = " Please click the link below to activate your Account
					http://localhost:8888//register/activateUser?email=$email&validationCode=$validationCode";
					Helper::sendMail($email, $subject, $msg);
				}
				if($role === "2"){	// if $role == 2(partner), send email to both partner user and admin
					// send email to partner user
					$subject = "Waitng Admin to activate your account";
					$msg = " Dear partner Please Wait for Admin to activate your account
					";
					Helper::sendMail($email, $subject, $msg);

					// send email to admin user with activate link
					// ** may have issue with $msg link.
					$adminEmail = "jinfang1969@gmail.com";
					$subject = "Partner $userName are waiting to be activate [$email]";
					$msg = " Contact this partner ($email) OR activate account now:
					http://localhost:8888//register/activateUser?email=$email&validationCode=$validationCode";
					Helper::sendMail($adminEmail, $subject, $msg);
				}

				Helper::redirect('/auth/users/login');

			}else{
				
			}

		}
	}

	/**
	* @des input validation
	*/
	public function inputValidation($firstName,$lastName,$userName,$email,$address,$password,$confirmPassword)
	{
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
				echo Helper::errorHandle($error);
			}
			//return false;

		}else{

			return true;
			
			}
	}

	/**
	* @des activate user get activate information from activate link
	*/
	public function activateUser()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$email 			= htmlspecialchars($_GET['email']);
			$validationCode = htmlspecialchars($_GET['validationCode']);

			if(User::findUser($email,$validationCode)){ // Check if user exists 
				User::activate($email,$validationCode);
				$msg = "Your account has been activated please login";
				View::renderTemplate('Auth/login.html',['msg' => $msg]);
			}else{
				$msg = "Your account cannot be activate";
				View::renderTemplate('Auth/login.html',['msg' => $msg]);
				echo $msg;
			}
		}
	}

}	
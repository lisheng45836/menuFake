<?php
namespace App\Controllers;
use App\Controllers\Auth\Helper;
use \Core\View;
use App\Models\Restaurant;
use App\Models\User;
use App\Controllers\Auth\Users;

class Admin extends \Core\Controller
{
	public function index(){
		$auth = Users::auth();
		if($auth){
			$user = Users::getUser();
			$userId = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 3){
			 	$userData = User::getAllUser();
			 	View::renderTemplate('Admin/admin.html',['auth'=>$auth,'userData'=>$userData,'userRole'=>$userRole]);
			 }else{
			 	Helper::redirect('/');
			}
		}else{
			Helper::redirect('/auth/users/login');
		}
	}

	public function activateUser(){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST['activate'])){
				$email = htmlspecialchars($_POST['email']);
				$validationCode = htmlspecialchars($_POST['validationCode']);
				User::activate($email,$validationCode);
				Helper::redirect('/admin');
			}else if(isset($_POST['deactivate'])){

				$email = htmlspecialchars($_POST['email']);
				$validationCode = htmlspecialchars($_POST['validationCode']);
				User::deactivate($email,$validationCode);
				Helper::redirect('/admin');
			}
		} 
	}

	public function editRestaurant(){
		$auth = Users::auth();
		if($auth){
			$user = Users::getUser();
			$userId = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 3){
				if($_SERVER['REQUEST_METHOD'] == 'GET'){
					$userId = $this->route_params['name'];
					$restaurantData = Restaurant::getRestaurantByOwner($userId);
					View::renderTemplate('Admin/restaurant.html',['restaurantData' => $restaurantData,'userId'=>$userId,'userRole'=>$userRole,'auth'=>$auth]);
				}
			}else{
				Helper::redirect('/auth/users/login');
			}
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$restaurantName = $this->route_params['name'];
			$title			= htmlspecialchars($_POST['title']);
			$cuisineName	= htmlspecialchars($_POST['cuisineName']);
			$openTime		= htmlspecialchars($_POST['openTime']);
			$minOrder		= htmlspecialchars($_POST['minOrder']);
			$description	= htmlspecialchars($_POST['description']);
			
			$address		= htmlspecialchars($_POST['address']);
			$cartType		= htmlspecialchars($_POST['cartType']);
			$userId 		= htmlspecialchars($_POST['userId']);
			if(isset($_POST['save'])){
				Restaurant::updateRestaurant($title,$cuisineName,$openTime,$minOrder,$description,$address,$cartType,$restaurantName);
				Helper::redirect("/admin/$userId/editRestaurant");
				

			}elseif (isset($_POST['delete'])) {
				Restaurant::deleteRestaurant($restaurantName);
				Helper::redirect("/admin/$userId/editRestaurant");
			}

		}
	}

	public function addRestaurant(){
		
			$userId 		= htmlspecialchars($_POST['userId']);
			$title			= htmlspecialchars($_POST['title']);
			$cuisineName	= htmlspecialchars($_POST['cuisineName']);
			$openTime		= htmlspecialchars($_POST['openTime']);
			$minOrder		= htmlspecialchars($_POST['minOrder']);
			$description	= htmlspecialchars($_POST['description']);
			$address		= htmlspecialchars($_POST['address']);
			$cartType		= htmlspecialchars($_POST['cartType']);

			if(isset($_FILES['ufile']['name'])){
				$name = isset($_FILES['ufile']['name']);
				$targetPath=$_SERVER['DOCUMENT_ROOT']."/img/cover/";
				$imagePath = $targetPath.basename($name);
				$path = "http://localhost:8888/img/cover/".$name;
				if(move_uploaded_file($_FILES['ufile']['tmp_name'],$imagePath)){
				//header('Location: ' . $_SERVER['HTTP_REFERER']);
					Helper::redirect("/admin/$userId/editRestaurant");
					echo $path;
				}else{
					echo "Nah";
				}
			}
			Restaurant::addRestaurant($title,$cuisineName,$openTime,$minOrder,$description,$path,$address,$cartType,$userId);
		
	}

	
}
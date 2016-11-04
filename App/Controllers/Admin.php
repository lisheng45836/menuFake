<?php
/****************************************************/
// Filename: Admin.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use App\Controllers\Auth\Helper;
use \Core\View;
use App\Models\Restaurant;
use App\Models\User;
use App\Controllers\Auth\Users;


/**
 * Admin controller. 
 * activateUser(),editRestaurant(), addRestaurant()
 */

class Admin extends \Core\Controller
{

	/**
	 * @des render admin page view. 
	 * 
	 */
	public function index()
	{
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

	/**
	 * @des activate and deactivate users
	 * 
	 */
	public function activateUser()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST['activate'])){
				$email = htmlspecialchars($_POST['email']);
				$validationCode = htmlspecialchars($_POST['validationCode']);
				User::activate($email,$validationCode);		// set activate status in database
				Helper::redirect('/admin');
			}else if(isset($_POST['deactivate'])){

				$email = htmlspecialchars($_POST['email']);
				$validationCode = htmlspecialchars($_POST['validationCode']);
				User::deactivate($email,$validationCode);	// set deactivate status in database
				Helper::redirect('/admin');
			}
		} 
	}


	/**
	 * @des edit Restaurant function for admin users.
	 * 
	 */
	public function editRestaurant()
	{
		$auth = Users::auth();
		if($auth){
			$user = Users::getUser();
			$userId = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 3){
				if($_SERVER['REQUEST_METHOD'] == 'GET'){
					$userId = $this->route_params['name'];	// get URL name params (user id)
					$restaurantData = Restaurant::getRestaurantByOwner($userId);	//get restaurant data by user id
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
			$closeTime		= htmlspecialchars($_POST['closeTime']);
			$minOrder		= htmlspecialchars($_POST['minOrder']);
			$description	= htmlspecialchars($_POST['description']);
			$address		= htmlspecialchars($_POST['address']);
			$cartType		= htmlspecialchars($_POST['cartType']);
			$userId 		= htmlspecialchars($_POST['userId']);
			$title 			= Helper::spaceTodash($title); //conver title string space to dash

			if(isset($_POST['save'])){ //if POST['save'] update restaurant information
				Restaurant::updateRestaurant($title,$cuisineName,$openTime,$closeTime,$minOrder,$description,$address,$cartType,$restaurantName);	
				Helper::redirect("/admin/$userId/editRestaurant");
				

			}elseif (isset($_POST['delete'])) { //if POST['delete'] delete restaurant information
				Restaurant::deleteRestaurant($restaurantName);
				Helper::redirect("/admin/$userId/editRestaurant");
			}

		}
	}

	/**
	 * @des add create new Restaurant
	 *
	 */
	public function addRestaurant()
	{
		$userId 		= htmlspecialchars($_POST['userId']);
		$title			= htmlspecialchars($_POST['title']);
		$cuisineName	= htmlspecialchars($_POST['cuisineName']);
		$openTime		= htmlspecialchars($_POST['openTime']);
		$closeTime		= htmlspecialchars($_POST['closeTime']);
		$minOrder		= htmlspecialchars($_POST['minOrder']);
		$description	= htmlspecialchars($_POST['description']);
		$address		= htmlspecialchars($_POST['address']);
		$cartType		= htmlspecialchars($_POST['cartType']);
		$title 			= Helper::spaceTodash($title); // 

		/**
		* upload image to server
		*/
		if(isset($_FILES['ufile']['name'])){
			$name = isset($_FILES['ufile']['name']);
			$targetPath=$_SERVER['DOCUMENT_ROOT']."/img/cover/"; //file location path
			$imagePath = $targetPath.basename($name);	//image path
			$path = "http://localhost:8888/img/cover/".$name;	//image server path(image URL)
			if(move_uploaded_file($_FILES['ufile']['tmp_name'],$imagePath)){ // move file
				Helper::redirect("/admin/$userId/editRestaurant");
				echo $path;	// return image path (image URL).
			}else{
				echo "Nah";
			}
		}
		//Create new Restaurant record in database
		Restaurant::addRestaurant($title,$cuisineName,$openTime,$closeTime,$minOrder,$description,$path,$address,$cartType,$userId);
		
	}

	
}
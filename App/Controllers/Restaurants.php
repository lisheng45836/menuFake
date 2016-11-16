<?php
/****************************************************/
// Filename: Restaurants.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use \Core\View;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Review;
use App\Controllers\Auth\Users;
use App\Controllers\Auth\Helper;

/**
* Restaurants controller
* Handler restaurant actions
*/
class Restaurants extends \Core\Controller
{
	/**
	* @des get menus for each Restaurant
	*/
	public function menus()
	{
		$name = $this->route_params['name'];
		//$name = preg_replace('/_/',' ',$name);
		$info = Restaurant::getRestaurantByName($name);
		$id	= $info[0]['id'];
		$menu = Menu::getMenu($id);
		$menus = Helper::uniqueArray($menu,'menuTitle');
		$auth = Users::auth();
		$user = Users::getUser();
		View::renderTemplate('Lists/restaurant.html',['restaurant' => $info,'menus' => $menus,'auth'=>$auth,'user'=>$user]);
	}

	public function foodOption()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			
			$foodId = htmlspecialchars($_GET['foodId']);
			$optionData = Menu::getFoodOption($foodId);
			$optionData = Helper::uniqueArray($optionData,'optionMenuName');

			if($optionData){
				echo json_encode($optionData);
			}else{
				echo false;
			}
			
		}
	}

	/**
	* @des redirect orders information
	*/
	public function orders()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			$auth = Users::auth();
			$restaurantId	= $_POST['restaurantId'];
			$cartType		= $_POST['cartType'];
			$user = Users::getUser();
			// jump to payment page .. 
			View::renderTemplate('Payment/checkOut.html',['auth'=>$auth,'restaurantId'=>$restaurantId,'cartType'=>$cartType,'user'=>$user]);
		}
	}

	/**
	* @des search restaurants 
	*/
	public function searchRestaurants()
	{
		$search = htmlspecialchars($_GET['search']);
		$auth = Users::auth();
		if($auth){
			$user = Users::getUser();
			$userID = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 2){
				$result = Restaurant::searchRestaurant($userID,$search);
				View::renderTemplate('Search/ownerSearch.html',['result'=>$result,'auth'=>$auth,'userRole'=>$userRole]);
			}
			if($userRole == 3){
				$result = Restaurant::searchRestaurantAdmin($search);
				View::renderTemplate('Search/adminSearch.html',['result'=>$result,'auth'=>$auth,'userRole'=>$userRole]);
			}
		}
	}

	/**
	* @des add new cuisine
	*/
	public function addCuisines()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$cuisineName = htmlspecialchars($_POST['cuisineName']);
			Restaurant::setCuisines($cuisineName);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}

	public function deleteCuisine()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$cuisineId = htmlspecialchars($_POST['cuisineId']);
			Restaurant::deleteCuisine($cuisineId);
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	}

	/**
	* @des redirect to reviews page
	*/
	public function reviews()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$auth = Users::auth();
			$name = $this->route_params['name']; //get restaurant name for URL
			$user = Users::getUser();
			$data = Restaurant::getRestaurantByName($name);
			$id = $data[0]['id']; // restaurant id
			$reviews = Review::getReview($id); //get reviews by restaurant id
			$overall = 0;
			//counting overall rating reviews
			$all = count($reviews);
			for($i=0; $i < $all; $i++){
				$overall += $reviews[$i]['overall'];
			}
			if($all != 0){
				$overall = $overall/$all;
			}
			
			Restaurant::updateRestaurantReviews($name,intval($overall));
			View::renderTemplate('Review/reviews.html',['name'=>$name,'reviews'=>$reviews,'overall'=>intval($overall),'auth'=>$auth,'user'=>$user]);
		}
	}

}

<?php
namespace App\Controllers;
use \Core\View;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Review;
use App\Controllers\Auth\Users;
use App\Controllers\Auth\Helper;
class Restaurants extends \Core\Controller
{
	public function menus(){

		$name = $this->route_params['name'];
		$name = preg_replace('/_/',' ',$name);
		$info = Restaurant::getRestaurantByName($name);
		$id	= $info[0]['id'];
		$menu = Menu::getMenu($id);
		$menus = Helper::uniqueArray($menu,'menuTitle');
		$auth = Users::auth();
		
		View::renderTemplate('Lists/restaurant.html',['restaurant' => $info,'menus' => $menus,'auth'=>$auth]);

	}

	public function orders(){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$auth = Users::auth();
				$restaurantId	= $_POST['restaurantId'];
				$cartType		= $_POST['cartType'];

				if($auth){
					$users = Users::getUser();
					// jump to payment page .. 
					View::renderTemplate('Payment/checkOut.html',['auth'=>$auth,'restaurantId'=>$restaurantId,'cartType'=>$cartType,'users'=>$users]);
				}
				if(!$auth){
					View::renderTemplate('Payment/checkOut.html',['auth'=>$auth,'restaurantId'=>$restaurantId,'cartType'=>$cartType]);
				}
		}
	}

	public function searchRestaurants(){
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

	public function reviews(){
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$auth = Users::auth();
			$name = $this->route_params['name'];
			$data = Restaurant::getRestaurantByName($name);
			$id = $data[0]['id'];
			$reviews = Review::getReview($id);
			$overall = 0;
			$all = count($reviews);
			for($i=0; $i < $all; $i++){
				$overall += $reviews[$i]['overall'];
			}
			if($all != 0){
				$overall = $overall/$all;
			}
			
			Restaurant::updateRestaurantReviews($name,intval($overall));
			View::renderTemplate('Review/reviews.html',['name'=>$name,'reviews'=>$reviews,'overall'=>intval($overall),'auth'=>$auth]);
		}
	}

}

<?php
namespace App\Controllers;
use \Core\View;
use App\Models\Restaurant;
use App\Models\Menu;
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
			if(Users::auth()){
				$data = $_POST['orderList'];
				$orderId = uniqid();
				var_dump($orderId);
				$user = Users::getUser();
				$userID = $user[0]['id'];
				Menu::setOrder($orderId,$userID);
				for($i=0;$i<count($data);$i++){
					$foodId 		= $data[$i]['foodId'];
					$restaurantId	= $data[$i]['restaurantId'];
					$price			= $data[$i]['price'];
					$foodTitle		= $data[$i]['foodTitle'];
					$qty			= $data[$i]['qty'];
					Menu::addOrder($orderId,$foodId,$restaurantId,$price,$foodTitle,$qty);
				}
				echo "$userID";
			}else{
				echo "not login, contiune";
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

}

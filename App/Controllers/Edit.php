<?php
/****************************************************/
// Filename: Edit.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use \Core\View;
use App\Controllers\Auth\Users;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Controllers\Auth\Helper;

/**
* Edit controller
* Handler Menu Edit request.
* 
*/
class Edit extends \Core\Controller
{
	/**
	 * @des render Menu Edit page view. 
	 * 
	 */
	public function menu()
	{
		$auth = Users::auth();
		if($auth){	//check if user is login
			$user = Users::getUser();
			$userRole = $user[0]['role'];
			if($userRole == 2 || $userRole == 3){	//check user Role (2 == partner 3 == admin)
				$title = $this->route_params['name'];
				$menuTitle = Menu::getMenuTitle($title);
				$id = $menuTitle[0]['restaurant_id'];
				$menu = Menu::getMenu($id);
				$menus = Helper::uniqueArray($menu,'menuTitle');	//return unique menu array
				View::renderTemplate('Partner/editMenu.html',['title'=>$title,'menuTitle' => $menuTitle,'auth'=>$auth,'userRole'=>$userRole,'menus'=>$menus]);
			}else{
				echo '<a href="/"> GO BACK </>';
			}
		}else{
			Helper::redirect('/auth/users/login');
		}
	}

	/**
	* @des edit menu fucntion 
	*/
	public function editMenu()
	{
		$auth = Users::auth();
		if($auth){
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				if(isset($_POST['edit'])){
					$foodTitle 		= htmlspecialchars($_POST['foodTitle']);
					$price 			= htmlspecialchars($_POST['price']);
					$description	= htmlspecialchars($_POST['description']);
					$foodId 		= htmlspecialchars($_POST['foodId']);
					$title 			= htmlspecialchars($_POST['title']);

					Menu::updateMenu($foodTitle,$price,$description,$foodId);
					Helper::redirect("/edit/$title/menu");
				}else if(isset($_POST['delete'])){
					$foodId 		= htmlspecialchars($_POST['foodId']);
					$title 			= htmlspecialchars($_POST['title']);

					Menu::deleteFood($foodId);
					Helper::redirect("/edit/$title/menu");
				}
			}
		}
	}

	/**
	* @des delete menu by title name
	*/
	public function deleteTitle()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$menuTitle = htmlspecialchars($_POST['menuTitle']);
			$title = htmlspecialchars($_POST['title']);
			Menu::deleteMenuByTitle($menuTitle);
			Helper::redirect("/edit/$title/menu");
		}
	}

	public function editRestaurant()
	{
		$auth = Users::auth();
		if($auth){
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

				$title 			= Helper::spaceTodash($title); //
				
				if(isset($_POST['save'])){
					Restaurant::updateRestaurant($title,$cuisineName,$openTime,$closeTime,$minOrder,$description,$address,$cartType,$restaurantName);
					Helper::redirect("http://localhost:8888/partner");
					
				}
				// echo $openTime;
			}
		}else{
			Helper::redirect('/auth/users/login');
		}
	}

	/**
	* @des add food into menu
	*/
	public function addFood()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$foodTitle 		= htmlspecialchars($_POST['foodTitle']);
			$price 			= htmlspecialchars($_POST['price']);
			$description 	= htmlspecialchars($_POST['description']);
			$menuId 		= htmlspecialchars($_POST['menuId']);
			$title 			= htmlspecialchars($_POST['title']);
			Menu::addFood($foodTitle,$price,$description,$menuId);
			Helper::redirect("/edit/$title/menu");
		}

	}

	/**
	* @des add new menu
	*/
	public function addMenus()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$auth = Users::auth();
			$user = Users::getUser();
			$userRole = $user[0]['role'];
			if($userRole == 2 || $userRole == 3){
				$title = $this->route_params['name'];
				View::renderTemplate('Partner/addMenu.html',['title' => $title,'userRole'=>$userRole,'auth'=>$auth]);
			}
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$menuTitle 		= htmlspecialchars($_POST['menuTitle']);
			$foodTitle 		= htmlspecialchars($_POST['foodTitle']);
			$price 			= htmlspecialchars($_POST['price']);
			$description 	= htmlspecialchars($_POST['description']);
			$title 			= htmlspecialchars($_POST['title']);
			$restaurant 	= Restaurant::getRestaurantByName($title);
			$restaurantId 	= $restaurant[0]['id'];	
			if(Menu::addMenu($menuTitle,$restaurantId)){ //add new menu
				$menuInfo = Menu::getMenuByName($menuTitle);
				$menuId = $menuInfo[0]['id'];
				Menu::addFood($foodTitle,$price,$description,$menuId);
				Helper::redirect("/edit/$title/menu");
			} 

		}
	}
}
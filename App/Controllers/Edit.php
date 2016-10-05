<?php
namespace App\Controllers;
use \Core\View;
use App\Controllers\Auth\Users;
use App\Models\Menu;
use App\Models\Restaurant;
use App\Controllers\Auth\Helper;
/**
* 
*/
class Edit extends \Core\Controller
{
	public function menu(){
		$title = $this->route_params['name'];
		$menuTitle = Menu::getMenuTitle($title);
		$id = $menuTitle[0]['restaurant_id'];
		$menu = Menu::getMenu($id);
		$menus = Helper::uniqueArray($menu,'menuTitle');
		$auth = Users::auth();
		View::renderTemplate('Partner/editMenu.html',['title'=>$title,'menuTitle' => $menuTitle,'auth'=>$auth,'menus'=>$menus]);
	}

	public function editMenu()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			if(isset($_POST['edit'])){
				$foodTitle = htmlspecialchars($_POST['foodTitle']);
				$price = htmlspecialchars($_POST['price']);
				$description = htmlspecialchars($_POST['description']);
				$foodId = htmlspecialchars($_POST['foodId']);
				$title = htmlspecialchars($_POST['title']);
				Menu::updateMenu($foodTitle,$price,$description,$foodId);
				Helper::redirect("/edit/$title/menu");
			}else if(isset($_POST['delete'])){
				$foodId = htmlspecialchars($_POST['foodId']);
				$title = htmlspecialchars($_POST['title']);
				Menu::deleteFood($foodId);
				Helper::redirect("/edit/$title/menu");
			}
			
		}
	}

	public function addFood()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$foodTitle = htmlspecialchars($_POST['foodTitle']);
			$price = htmlspecialchars($_POST['price']);
			$description = htmlspecialchars($_POST['description']);
			$menuId = htmlspecialchars($_POST['menuId']);
			$title = htmlspecialchars($_POST['title']);
			Menu::addFood($foodTitle,$price,$description,$menuId);
			Helper::redirect("/edit/$title/menu");
		}

	}

	public function addMenus()
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET'){
			$title = $this->route_params['name'];
			View::renderTemplate('Partner/addMenu.html',['title' => $title]);
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
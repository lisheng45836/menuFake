<?php
namespace App\Controllers;
use App\Controllers\Auth\Helper;
use \Core\View;
use App\Models\Restaurant;
use App\Controllers\Auth\Users;

class Partner extends \Core\Controller
{
	public function index(){
		$auth = Users::auth();
		$user = Users::getUser();
		$userId = $user['id'];
		$userRole = $user['role'];
		if($userRole == 2){
		 	$restaurantData = Restaurant::getRestaurantByOwner($userId);
		 	View::renderTemplate('Partner/panel.html',['auth'=>$auth,'restaurantData'=>$restaurantData]);
		 }else{
		 	Helper::redirect('/');
		}


	}
}
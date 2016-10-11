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
		if($auth){
			$user = Users::getUser();
			$userId = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 2){
			 	$restaurantData = Restaurant::getRestaurantByOwner($userId);
			 	View::renderTemplate('Partner/panel.html',['auth'=>$auth,'restaurantData'=>$restaurantData,'user'=>$user,'userId'=>$userId,'userRole'=>$userRole]);
			 }else{
			 	Helper::redirect('/');
			}
		}else{
			Helper::redirect('/auth/users/login');
		}

	}
}
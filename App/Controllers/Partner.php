<?php
/****************************************************/
// Filename: Partner.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use App\Controllers\Auth\Helper;
use \Core\View;
use App\Models\Restaurant;
use App\Controllers\Auth\Users;

/**
* Business partner controller
*/
class Partner extends \Core\Controller
{
	public function index()
	{
		$auth = Users::auth();
		if($auth){
			$user = Users::getUser();
			$userId = $user[0]['id'];
			$userRole = $user[0]['role'];
			if($userRole == 2){
			 	$restaurantData = Restaurant::getRestaurantByOwner($userId);
			 	$cuisines = Restaurant::getCuisines();
			 	View::renderTemplate('Partner/panel.html',['auth'=>$auth,'restaurantData'=>$restaurantData,'cuisines'=>$cuisines,'user'=>$user,'userId'=>$userId,'userRole'=>$userRole]);
			 }else{
			 	Helper::redirect('/');
			}
		}else{
			Helper::redirect('/auth/users/login');
		}

	}
}
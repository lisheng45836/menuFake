<?php
/****************************************************/
// Filename: Reviews.php
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
* Reviews controller
*/
class Reviews extends \Core\Controller
{
	/**
	* @des Set rating & comments
	*/
	public function rating()
	{
		$auth = Users::auth();
		$user = Users::getUser();
		$userID = $user[0]['id'];

		$food 		= htmlspecialchars($_POST["food"]);
		$value 		= htmlspecialchars($_POST["value"]);
		$speed 		= htmlspecialchars($_POST["speed"]);
		$comment	= htmlspecialchars($_POST["comment"]);
		$restTitle 	= htmlspecialchars($_POST["restTitle"]);
		$data = Restaurant::getRestaurantByName($restTitle);
		$id = $data[0]['id'];
		$overall = ($food+$value+$speed)/3; // cal overall rating
		Review::setComment($food,$value,$speed,$comment,$overall,$id,$userID);
	}

}
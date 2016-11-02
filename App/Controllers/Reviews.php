<?php
namespace App\Controllers;
use \Core\View;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Review;
use App\Controllers\Auth\Users;
use App\Controllers\Auth\Helper;

class Reviews extends \Core\Controller
{
	//  Set rating & comments
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
		$overall = ($food+$value+$speed)/3;
		Review::setComment($food,$value,$speed,$comment,$overall,$id,$userID);
		

	}


}
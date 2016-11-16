<?php
/****************************************************/
// Filename: Search.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use \Core\View;
use App\Models\Restaurant;
use App\Controllers\Auth\Users;

/**
* Search controller (index page)
*/
class Search extends \Core\Controller
{
	public function index()
	{
		if(isset($_GET["location"])&&isset($_GET["cartType"])){
			$address = $_GET["location"];
			$cartType = $_GET["cartType"];
			$locations = explode(",",$address);
			$locations[0];
			$location = ucfirst($locations[0]); // first character uppercase
			$location = preg_replace('/ /','_',$location);
			$results = Restaurant::getLists($location,$cartType);
			$cuisines = Restaurant::getCuisines();
			$totalResults = count($results);
			$auth = Users::auth();
			$user = Users::getUser();
			View::renderTemplate('Lists/lists.html',['result' => $results,'location' => $location,'cuisines'=>$cuisines,'auth'=>$auth,'user'=>$user]);
		}
	}

	public function refind()
	{
		$location = $this->route_params['name'];
		
		
		if(isset($_GET["cuisineNames"])&&isset($_GET["cartType"])){
			$cuisineName = $_GET["cuisineNames"];
			if($cuisineName[0] === "all")
			{
				$cuisineName = "all";
			}
			$cartType = $_GET["cartType"];
			$refindSearch = Restaurant::getRefindSearch($cuisineName,$cartType,$location);
			echo json_encode($refindSearch);
			//View::renderTemplate('Lists/lists.html',['result'=>$refindSearch,'location' => $location]);
		}
		
	}
}
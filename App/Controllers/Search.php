<?php

namespace App\Controllers;
use \Core\View;
use App\Models\Restaurant;

class Search extends \Core\Controller
{
	public function index()
	{
		if(isset($_GET["location"])&&isset($_GET["cartType"])){
			$location = $_GET["location"];
			$cartType = $_GET["cartType"];
			$location = ucfirst($location); // first character uppercase
			$results = Restaurant::getLists($location,$cartType);
			View::renderTemplate('Lists/lists.html',['result' => $results ,'location' => $location]);
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
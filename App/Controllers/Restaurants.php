<?php

namespace App\Controllers;
use \Core\View;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Controllers\Auth\Users;
class Restaurants extends \Core\Controller
{
	public function order(){

		$name = $this->route_params['name'];
		$name = preg_replace('/_/',' ',$name);
		$info = Restaurant::getRestaurant($name);
		$id	= $info[0]['id'];
		$menu = Menu::getMenu($id);
		$menus = $this->unique_array($menu,'menuTitle');
		$auth = Users::auth();
		View::renderTemplate('Lists/restaurant.html',['restaurant' => $info,'menus' => $menus,'auth'=>$auth]);

	}

	public function unique_array($array, $key) { 
		    $tempArray = array(); 
		    $i = 0; 
		    $keyArray = array(); 
		    
		    foreach($array as $val) { 
		        if (!in_array($val[$key], $keyArray)) { 
		            $keyArray[$i] = $val[$key]; 
		            $tempArray[$i] = $val; 
		            //var_dump($temp_array[$i]);
		        }else{
		        	$i++;
		        	unset($val[$key]);
		        	$tempArray[$i] = $val; 
		        	//var_dump($i);
		        	$i++;
		        }   
		    }
		    return $tempArray; 
		} 
}

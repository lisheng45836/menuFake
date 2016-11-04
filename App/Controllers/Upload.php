<?php
/****************************************************/
// Filename: Upload.php
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
* Upload controller
* Handler image Upload action
*/
class Upload extends \Core\Controller
{
	public function uploadPhoto()
	{
		if(isset($_FILES['ufile']['name'])){

			$name = $_FILES['ufile']['name'];
			$restaurantId = $_POST['restaurantId'];
	
			$targetPath=$_SERVER['DOCUMENT_ROOT']."/img/cover/";
			$imagePath = $targetPath.basename($name);
			// $imageFileType = pathinfo($file,PATHINFO_EXTENSION); // may use for file check
			$path = "http://localhost:8888/img/cover/".$name;
			Restaurant::updateRestaurantImage($restaurantId,$path);

			if(move_uploaded_file($_FILES['ufile']['tmp_name'],$imagePath)){
				//header('Location: ' . $_SERVER['HTTP_REFERER']);
				echo $path;
			}else{
				echo "f";
			}
		}else{
			$default = "http://localhost:8888/img/cover/default.jpg";
			echo $default;
		}
		
	}

}
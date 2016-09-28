<?php

namespace App\Models;

use PDO;

class Restaurant extends \Core\Model
{
	public static function getLists($search,$cartType)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE cartType = ? AND address LIKE ? ");
			$stmt->execute(array($cartType,"%$search%"));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch (PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getRestaurant($name)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE title = ? ");
			$stmt->execute(array($name));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getRefindSearch($cuisineName=[],$cartType,$location)
	{
		try{

			$db = static::getDB();
			$cuisine = implode(',', array_fill(0, count($cuisineName), '?'));
			if($cuisineName == "all"){

				$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE cartType = ? AND address LIKE ?");
				$stmt->execute(array($cartType,"%$location%"));
			}else{

				$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE cuisineName in ($cuisine) AND cartType = '$cartType' AND address LIKE '%$location%'");
				$stmt->execute($cuisineName);
			}

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

}
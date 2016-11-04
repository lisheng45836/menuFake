<?php

namespace App\Models;

use PDO;

/**
* Partners Model
*/
class Partners extends \Core\Model
{	
	/**
	* @des get restaurant from database by user id 
	* @param $userId
	* @return $result
	* this function may be duplicated. need improve
	*/
	public static function getMyRestaurant($userId)
	{
		try{
			$db = static::getDB();
			$stmt=$db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE owner = ?");
			$stmt->execute(array($userId));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}
}
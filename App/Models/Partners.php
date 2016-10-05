<?php

namespace App\Models;

use PDO;

class Partners extends \Core\Model
{
	public static function getMyRestaurant($userId){
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
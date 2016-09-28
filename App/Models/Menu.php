<?php

namespace App\Models;

use PDO;

class Menu extends \Core\Model
{

	public static function getMenu($id)
	{
		try{
			$db = static::getDB();
			$stmt=$db->prepare("SELECT menuTitle,foodTitle,food.id,price,restaurant_id from menu,food where menu.id=food.menu_id AND restaurant_id = ?");

			$stmt->execute(array($id));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}
}
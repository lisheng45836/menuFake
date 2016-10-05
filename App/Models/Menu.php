<?php

namespace App\Models;

use PDO;

class Menu extends \Core\Model
{

	public static function getMenu($id){
		try{
			$db = static::getDB();
			$stmt=$db->prepare("SELECT menuTitle,foodTitle,food.id,price,description,restaurant_id from menu,food where menu.id=food.menu_id AND restaurant_id = ?");

			$stmt->execute(array($id));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getMenuTitle($title){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT menu.id,menu.menuTitle,restaurant_id FROM menu,restaurant WHERE menu.restaurant_id = restaurant.id AND restaurant.title = ?");
			$stmt->execute(array($title));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getMenuByName($name){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,menuTitle,restaurant_id FROM menu WHERE menuTitle =?");
			$stmt->execute(array($name));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function updateMenu($foodTitle,$price,$description,$foodId){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE food SET foodTitle = :foodTitle, price=:price, description=:description WHERE id = :foodId");
			$stmt->bindParam(':foodTitle',$foodTitle);
			$stmt->bindParam(':price',$price);
			$stmt->bindParam(':description',$description);
			$stmt->bindParam(':foodId',$foodId);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}


	public static function addMenu($menuTitle,$restaurantId){
		try{
			$db = $db = static::getDB();
			$stmt = $db->prepare("INSERT INTO menu(menuTitle,restaurant_id)VALUES(:menuTitle,:restaurantId)");
			$stmt->bindParam(':menuTitle',$menuTitle);
			$stmt->bindParam(':restaurantId',$restaurantId);
			$stmt->execute();
			return true;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function addFood($foodTitle,$price,$description,$menuId){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO food(foodTitle,price,description,menu_id)VALUES(:foodTitle,:price,:description,:menuId)");
			$stmt->bindParam(':foodTitle',$foodTitle);
			$stmt->bindParam(':price',$price);
			$stmt->bindParam(':description',$description);
			$stmt->bindParam(':menuId',$menuId);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function deleteFood($foodId){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("DELETE FROM food WHERE id = :foodId");
			$stmt->bindParam(':foodId',$foodId);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function setOrder($cartId,$userID){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO cart(cartId,userId) VALUES (:cartId,:userId)");
			$stmt->bindParam(':cartId',$cartId);
			$stmt->bindParam(':userId',$userID);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function addOrder($orderId,$foodId,$restaurantId,$price,$foodTitle,$qty){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO orderItem(orderId,foodId,restaurantId,foodTitle,qty,price)VALUES(:orderId,:foodId,:restaurantId,:foodTitle,:qty,:price)");
			$stmt->bindParam(':orderId'		,$orderId);
			$stmt->bindParam(':foodId'		,$foodId);
			$stmt->bindParam(':restaurantId',$restaurantId);
			$stmt->bindParam(':foodTitle'	,$foodTitle);
			$stmt->bindParam(':qty'			,$qty);
			$stmt->bindParam(':price'		,$price);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}
}
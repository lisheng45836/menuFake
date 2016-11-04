<?php
/****************************************************/
// Filename: Menu.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Models;

use PDO;

/**
* Menu Model
*/
class Menu extends \Core\Model
{
	/**
	* @des get menu information by restaurant id
	* @param $id, restaurant id
	* @return $result, menu data
	*/
	public static function getMenu($id)
	{
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

	/**
	* @des get menu information by restaurant title
	* @param $title, restaurant title
	* @return $result, menu data
	*/
	public static function getMenuTitle($title)
	{
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

	/**
	* @des get menu information by menu title
	* @param $title, menu title
	* @return $result, menu data
	*/
	public static function getMenuByName($name)
	{
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

	/**
	* @des update food information by food id
	* @param $foodTitle,$price,$description,$foodId
	*/
	public static function updateMenu($foodTitle,$price,$description,$foodId)
	{
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

	/**
	* @des create new menu record
	* @param $menuTitle,$restaurantId
	* @return bool
	*/
	public static function addMenu($menuTitle,$restaurantId)
	{
		try{
			$db = $db = static::getDB();
			$stmt = $db->prepare("INSERT INTO menu(menuTitle,restaurant_id)VALUES(:menuTitle,:description,:restaurantId)");
			$stmt->bindParam(':menuTitle',$menuTitle);
			$stmt->bindParam(':restaurantId',$restaurantId);
			$stmt->execute();
			return true;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des create new food record by menu id
	* @param $foodTitle,$price,$description,$menuId
	*/
	public static function addFood($foodTitle,$price,$description,$menuId)
	{
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

	/**
	* @des delete menu record from database by title
	* @param $title
	*/
	public static function deleteMenuByTitle($title)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("DELETE FROM menu WHERE menuTitle = :title");
			$stmt->bindParam(':title',$title);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des delete food record from database by food id
	* @param $title
	*/
	public static function deleteFood($foodId)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("DELETE FROM food WHERE id = :foodId");
			$stmt->bindParam(':foodId',$foodId);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des set new order into database 
	* @param $cartId,$userId
	*/
	public static function setOrder($cartId,$userID)
	{
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

	/**
	* @des create new order 
	* @param $orderId,$foodId,$restaurantId,$price,$foodTitle,$qty
	*/
	public static function addOrder($orderId,$foodId,$restaurantId,$price,$foodTitle,$qty)
	{
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
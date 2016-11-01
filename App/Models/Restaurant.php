<?php

namespace App\Models;

use PDO;

class Restaurant extends \Core\Model
{
	public static function getLists($search,$cartType)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType,owner FROM restaurant WHERE cartType = ? AND address LIKE ? ");
			$stmt->execute(array($cartType,"%$search%"));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch (PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getRestaurantByName($name)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,minOrder,description,image_path,address,cartType,overall FROM restaurant WHERE title = ? ");
			$stmt->execute(array($name));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function getRestaurantByOwner($userId)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType,owner,overall FROM restaurant WHERE owner = ? ");
			$stmt->execute(array($userId));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function addRestaurant($title,$cuisineName,$openTime,$closeTime,$minOrder,$description,$path,$address,$cartType,$userId)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO restaurant(title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType,owner)VALUES(:title,:cuisineName,:openTime,:closeTime,:minOrder,:description,:image_path,:address,:cartType,:owner)");

			$stmt->bindParam(':title',$title);
			$stmt->bindParam(':cuisineName',$cuisineName);
			$stmt->bindParam(':openTime',$openTime);
			$stmt->bindParam(':closeTime',$closeTime);
			$stmt->bindParam(':minOrder',$minOrder);
			$stmt->bindParam(':description',$description);
			$stmt->bindParam(':image_path',$path);
			$stmt->bindParam(':address',$address);
			$stmt->bindParam(':cartType',$cartType);
			$stmt->bindParam(':owner',$userId);
			$stmt->execute();

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function deleteRestaurant($restaurantName)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("DELETE FROM restaurant WHERE title = ?");
			$stmt->execute(array($restaurantName));
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function updateRestaurant($title,$cuisineName,$openTime,$closeTime,$minOrder,$description,$address,$cartType,$restaurantName)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE restaurant SET title =:title,cuisineName =:cuisineName,openTime=:openTime,closeTime =:closeTime ,minOrder =:minOrder,description=:description,address=:address,cartType=:cartType WHERE title =:restTitle");
			$stmt->bindParam(':title',$title);
			$stmt->bindParam(':cuisineName',$cuisineName);
			$stmt->bindParam(':openTime',$openTime);
			$stmt->bindParam(':closeTime',$closeTime);
			$stmt->bindParam(':minOrder',$minOrder);
			$stmt->bindParam(':description',$description);
			$stmt->bindParam(':address',$address);
			$stmt->bindParam(':cartType',$cartType);
			$stmt->bindParam(':restTitle',$restaurantName);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function updateRestaurantReviews($title,$overall){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE restaurant SET overall =:overall WHERE title =:title");
			$stmt->bindParam(':overall',$overall);
			$stmt->bindParam(':title',$title);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function updateRestaurantImage($restaurantId,$imagePath){
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE restaurant SET image_path =:imagePath WHERE id =:restaurantId");
			$stmt->bindParam(':imagePath',$imagePath);
			$stmt->bindParam(':restaurantId',$restaurantId);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function searchRestaurant($userID,$search)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType,owner FROM restaurant WHERE owner = ? AND title LIKE ?");
			$stmt->execute(array($userID,"%$search%"));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function searchRestaurantAdmin($search)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType,owner FROM restaurant WHERE title LIKE ? ");
			$stmt->execute(array("%$search%"));
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

				$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE cartType = ? AND address LIKE ?");
				$stmt->execute(array($cartType,"%$location%"));
			}else{

				$stmt = $db->prepare("SELECT id,title,cuisineName,openTime,closeTime,minOrder,description,image_path,address,cartType FROM restaurant WHERE cuisineName in ($cuisine) AND cartType = '$cartType' AND address LIKE '%$location%'");
				$stmt->execute($cuisineName);
			}

			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

}
<?php
/****************************************************/
// Filename: Review.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Models;

use PDO;
/**
* Review model
*/
class Review extends \Core\Model
{
	/**
	* @des create new comment record
	* @param $food,$value,$speed,$comment,$overall,$id,$userID
	*/
	public static function setComment($food,$value,$speed,$comment,$overall,$id,$userID)
	{

		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO comments(food,value,speed,comments,overall,uid,restaurantId)VALUES(:food,:value,:speed,:comment,:overall,:userId,:restaurantId)");

			$stmt->bindParam(':food',$food);
			$stmt->bindParam(':value',$value);
			$stmt->bindParam(':speed',$speed);
			$stmt->bindParam(':comment',$comment);
			$stmt->bindParam(':overall',$overall);
			$stmt->bindParam(':userId',$userID);
			$stmt->bindParam(':restaurantId',$id);
			$stmt->execute();

		}catch (PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des get all reviews by restaurant id
	* @param $id, restraurant id
	* @return comments data
	*/
	public static function getReview($id)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT food,value,speed,overall,comments,time FROM comments WHERE restaurantId=?");
			$stmt->execute(array($id));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		}catch (PODException $e){
			echo $e->getMessage();
		}
	}

}
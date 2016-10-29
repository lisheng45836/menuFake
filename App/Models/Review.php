<?php

namespace App\Models;

use PDO;

class Review extends \Core\Model
{
	public static function setComment($food,$value,$speed,$comment,$overall,$id,$userID){

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

	public static function getReview($id){

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
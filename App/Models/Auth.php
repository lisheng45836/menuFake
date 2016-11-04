<?php
/****************************************************/
// Filename: Auth.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Models;

use PDO;

/**
* Auth Model
*/
class Auth extends \Core\Model
{
	/**
	* @des get user information from database by user email
	* @param $email
	* @return $result, user data 
	*/
	public static function getUserInfo($email)
	{
		try{
			$db = static::getDB();
			$stmt=$db->prepare("SELECT id,firstName,lastName,userName,email,address,role,password FROM users WHERE email =? AND activate = 1");
			$stmt->execute(array($email));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;

		}catch(PODException $e){
			echo $e->getMessage();
		}

	}

	/**
	* @des change validation code by email
	* @param $code, $email
	* @return bool
	*/
	public static function updateValidation($code,$email)
	{
		try{

			$db = static::getDB();
			$stmt=$db->prepare("UPDATE users set validationCode = :code WHERE email = :email");
			$stmt->bindParam(':code',$code);
			$stmt->bindParam(':email',$email);
			$stmt->execute();
			return true;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des change password
	* @param $newPassword, $email
	*/
	public static function updatePassword($newPassword,$email)
	{
		$newPassword = md5($newPassword);
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users SET password = :newPassword, validationCode = 0 WHERE email =:email");
			$stmt->bindParam(':newPassword',$newPassword);
			$stmt->bindParam(':email',$email);
			$stmt->execute();
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}
}
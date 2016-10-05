<?php 

namespace App\Models;

use PDO;

/**
* 
*/
class User extends \Core\Model
{
	

	public static function register($firstName,$lastName,$userName,$email,$password,$address,$role){
		$password   	= md5($password);
		$validationCode = md5($userName + microtime());

		try{
			$db = static::getDB();
			$stmt = $db->prepare("INSERT INTO users(firstName,lastName,userName,email,password,address,validationCode,activate,role)VALUES(:firstName,:lastName,:userName,:email,:password,:address,:validationCode,0,:role)");

			$stmt->bindParam(':firstName'		,$firstName);
			$stmt->bindParam(':lastName'		,$lastName);
			$stmt->bindParam(':userName'		,$userName);
			$stmt->bindParam(':email'			,$email);
			$stmt->bindParam(':password'		,$password);
			$stmt->bindParam(':address'			,$address);
			$stmt->bindParam(':validationCode'	,$validationCode);
			$stmt->bindParam(':role'			,$role);
			$stmt->execute();
			return $validationCode;
		}catch (PODException $e){
			echo $e->getMessage();
		}

	}

	public static function userExists($userName){

		try{
			$db 	= static::getDB();
			$stmt 	= $db->prepare("SELECT COUNT(userName) FROM users WHERE userName = ?");
			$stmt->execute(array($userName));
			if($stmt->fetchColumn() > 0){
				return true;
			}else{
				return false;
			}
		}catch (PODException $e){
			echo $e->getMessage();
		}
	}

	public static function emailExists($email){

		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT COUNT(email) FROM users WHERE email =?");
			$stmt->execute(array($email));
			if($stmt->fetchColumn() > 0){
				return true;
			}else{
				return false;
			}

		}catch(PODException $e){
			echo $e->getMessage();
		}

	}

	public static function findUser($email,$validationCode){

		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT COUNT(id) FROM users WHERE email = ? AND validationCode =?");
			$stmt->execute(array($email,$validationCode));
			if($stmt->fetchColumn() > 0){
				return true;
			}else{
				return false;
			}
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	public static function activate($email,$validationCode){

		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users SET activate = 1, validationCode = 0 WHERE email = ? AND validationCode = ?");
			$stmt->execute(array($email,$validationCode)); // Check this Later. may have bugs.

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}
}
<?php 
/****************************************************/
// Filename: User.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Models;

use PDO;

/**
* User model
*/
class User extends \Core\Model
{
	/**
	* @des create new user record into database
	* @param $firstName,$lastName,$userName,$email,$password,$address,$role
	* @return $validationCode
	*/
	public static function register($firstName,$lastName,$userName,$email,$password,$address,$role)
	{
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

	/**
	* @des check if user exists
	* @param $userName
	* @return bool
	*/
	public static function userExists($userName)
	{
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

	/**
	* @des check if email exists
	* @param $email
	* @return bool
	*/
	public static function emailExists($email)
	{

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

	/**
	* @des find user by email and validationCode
	* @param $email,$validationCode
	* @return bool
	*/
	public static function findUser($email,$validationCode)
	{
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

	/**
	* @des search for user and get user data
	* @param $search
	* @return users data
	*/
	public static function searchUser($search)
	{
		try{

			$db = static::getDB();
			$stmt = $db->prepare("SELECT id,firstName,lastName,userName,email,password,address,validationCode,activate,role FROM users WHERE userName LIKE ? OR email LIKE ? OR firstName LIKE ? OR lastName LIKE ?");
			$stmt->execute(array("%$search%","%$search%","%$search%","%$search%"));
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des get all user information 
	*/
	public static function getAllUser()
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id, firstName,lastName,userName,email,address,validationCode,activate,role FROM users");
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}


	public static function getUserById($userId)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("SELECT id, firstName,lastName,userName,email,address,validationCode,activate,role FROM users WHERE id = :userId");
			$stmt->bindParam(':userId',$userId);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des change user information
	*/
	public static function updateUser($userId,$firstName,$lastName,$userName,$email,$address)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users SET firstName = :firstName, lastName = :lastName, userName =:userName, email=:email, address = :address WHERE id = :userId");
			$stmt->bindParam(':firstName',$firstName);
			$stmt->bindParam(':lastName',$lastName);
			$stmt->bindParam(':userName',$userName);
			$stmt->bindParam(':email',$email);
			$stmt->bindParam(':address',$address);
			$stmt->bindParam(':userId',$userId);
			$stmt->execute();
			
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des change activate status to 1
	*/
	public static function activate($email,$validationCode)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users SET activate = 1, validationCode = 0 WHERE email = ? AND validationCode = ?");
			$stmt->execute(array($email,$validationCode)); // Check this Later. may have bugs.

		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

	/**
	* @des change activate status to 2, deactivate
	*/
	public static function deactivate($email,$validationCode)
	{
		try{
			$db = static::getDB();
			$stmt = $db->prepare("UPDATE users SET activate = 0, validationCode = 0 WHERE email = ? AND validationCode = ?");
			$stmt->execute(array($email,$validationCode));
		}catch(PODException $e){
			echo $e->getMessage();
		}
	}

}
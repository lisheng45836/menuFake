<?php
/****************************************************/
// Filename: Model.php
// Created: Lisheng Liu
/****************************************************/

namespace Core;

use PDO;
use App\Config;

/**
 * Base Model Database Connection PDO
 */

abstract class Model
{
	/**
	* @des init database setup PHP PDO, password,username,etc
	* @return $db 
	*/
	protected static function getDB()
	{
			try{
				$dbInfo = 'mysql:host='.Config::DB_HOST. ';dbname='.Config::DB_NAME;
				$db = new PDO($dbInfo, Config::DB_USER,Config::DB_PASSWORD);
				$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

			}catch(PDOException $e){
				die("ERROR: ".$e->getMessage());
			}

		return $db;

		
	}
}

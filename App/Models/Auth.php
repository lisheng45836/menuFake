<?php

namespace App\Models;

use PDO;

class Auth extends \Core\Model
{
	public static function getPassword($email){

		try{
			$db = static::getDB();
			$stmt=$db->prepare("SELECT password,id FROM customer WHERE email =? AND activate = 1");
			$stmt->execute(array($email));
			$result = $stmt->fetch();
			return $result['password'];
	
			//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
		}catch(PODException $e){
			echo $e->getMessage();
		}

	}
}
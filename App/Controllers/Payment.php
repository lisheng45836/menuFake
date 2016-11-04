<?php
/****************************************************/
// Filename: Payment.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use \Core\View;
use App\Controllers\Auth\Users;
use App\Controllers\Auth\Helper;
use App\Models\Menu;

/**
* Payment controller
* Handler payment process
* Issue: MISSING creidt card payment 
*/
class Payment extends \Core\Controller
{
	/**
	* @des check out process. get payment information 
	* and send Email shopping list to customer.
	*/
	public function checkOut()
	{
		$auth = Users::auth();

		$firstName 	= htmlspecialchars($_POST['firstName']);
		$lastName 	= htmlspecialchars($_POST['lastName']);
		$email		= htmlspecialchars($_POST['email']);
		$address	= htmlspecialchars($_POST['address']);
		$phone		= htmlspecialchars($_POST['phone']);
		$totalPrice	= htmlspecialchars($_POST['totalPrice']);
		// email info
		$subject = "Your Order Detail";
		$str = [];
		$food = $_POST['foodTitle'];
		for($i=0;$i<count($food);$i++){

			$foodTitle 		= htmlspecialchars($_POST['foodTitle'][$i]);
			$foodId 		= htmlspecialchars($_POST['foodId'][$i]);
			$price			= htmlspecialchars($_POST['price'][$i]);
			$qty			= htmlspecialchars($_POST['qty'][$i]);
			$restaurantId	= htmlspecialchars($_POST['restaurantId']);
	
			$str[] = "<strong>$foodTitle</strong> : x $qty <br> Price: $price <br>";
				
		}
		$msg = "Hello $email <br> Here is your orders<br>". implode(" ",$str)."<storng>Total Price</storng>: $totalPrice";

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: jinfang1969@gmail.com";
		Helper::sendMail($email,$subject,$msg,$headers); 

		if($auth){	//if login save into database 
			$orderId = uniqid();	//create unique order id
			$user = Users::getUser();
			$userID = $user[0]['id'];
			Menu::setOrder($orderId,$userID);	//set new order 
			for($i=0;$i<count($food);$i++){

				$foodTitle 		= htmlspecialchars($_POST['foodTitle'][$i]);
				$foodId 		= htmlspecialchars($_POST['foodId'][$i]);
				$price			= htmlspecialchars($_POST['price'][$i]);
				$qty			= htmlspecialchars($_POST['qty'][$i]);
				$restaurantId	= htmlspecialchars($_POST['restaurantId']);
		
				Menu::addOrder($orderId,$foodId,$restaurantId,$price,$foodTitle,$qty);
			}
		}

		echo $msg.'<a href="/">Go Back</a>';

	}
}
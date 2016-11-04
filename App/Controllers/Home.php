<?php
/****************************************************/
// Filename: Home.php
// Created: Lisheng Liu
/****************************************************/

namespace App\Controllers;

use \Core\View;
use App\Controllers\Auth\Users;

/**
* Home controller
* Render home page view
* 
*/
class Home extends \Core\Controller
{
	public function indexAction()
	{
		$auth = Users::auth();
		View::renderTemplate('Home/index.html',['auth'=>$auth]);

	}


}
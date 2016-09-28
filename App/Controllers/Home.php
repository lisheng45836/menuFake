<?php
namespace App\Controllers;
use \Core\View;
use App\Controllers\Auth\Users;

class Home extends \Core\Controller
{
	public function indexAction()
	{
		$auth = Users::auth();
		//var_dump($auth);
		View::renderTemplate('Home/index.html',['auth'=>$auth]);

	}


}
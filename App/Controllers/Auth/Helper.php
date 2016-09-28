<?php 
namespace App\Controllers\Auth;

class Helper{	

	public static function errorHandle($error){
		$error= <<<HTML
	<div class="alert alert-danger alert-dismissible" role="alert">
	  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	<strong>Warning!</strong> $error
	 </div>
HTML;
		return $error;
	}

	public static function redirect($url){
		return header('location:' .$url);
		exit;
	}

}
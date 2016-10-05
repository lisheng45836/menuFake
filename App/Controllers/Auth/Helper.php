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

	public static function sendMail($email, $subject, $msg){
		$headers = "From: aliu45836@hotmail.com";
		return mail($email, $subject, $msg, $headers);
	}

	public static function uniqueArray($array, $key) { 
		    $tempArray = array(); 
		    $i = 0; 
		    $keyArray = array(); 
		    
		    foreach($array as $val) { 
		        if (!in_array($val[$key], $keyArray)) { 
		            $keyArray[$i] = $val[$key]; 
		            $tempArray[$i] = $val; 
		            //var_dump($temp_array[$i]);
		        }else{
		        	$i++;
		        	unset($val[$key]);
		        	$tempArray[$i] = $val; 
		        	//var_dump($i);
		        	$i++;
		        }   
		    }
		    //var_dump($tempArray); 
		    return $tempArray; 
	} 
}
<?php
/****************************************************/
// Filename: Router.php
// Created: Lisheng Liu
/****************************************************/

namespace Core;
/**
 * Router
 */

class Router
{
	protected $routers = [];

	protected $params = [];

	/**
	 * @des Add a route to routing table
	 * @param $route
	 * @param $params = []
	 */
	public function add($route, $params = [])
	{

		// escape forward slashes
		$route = preg_replace('/\//','\\/',$route);

		// covert variables {**}
		$route = preg_replace('/\{([a-z]+)\}/','(?P<\1>[a-z-]+)', $route); 

		// convert variables with custom regular expressions e.g. {id:d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		// add start and end delimiters and case insensitive
		$route = '/^' . $route . '$/i';

		$this->routers[$route] = $params;
		//var_dump( $route);
	}

	/**
	 * @des Match the route in the routing table
	 * @param $url
	 * @return bool 
	 */

	public function match($url)
	{
		$url = $this->removeQueryStringVariables($url);
		foreach ($this->routers as $route => $params) {
			if(preg_match($route,$url,$matches)){

				foreach ($matches as $key => $match) {
					if(is_string($key)){
						$params[$key] = $match;
					}
				}

				$this->params = $params;
				return true;
			}
		}

		return false;
	}

	/**
	 * @des Dispatch the route, creating & call the controller object and action method.
	 * @param $url
	 */

	public function dispatch($url)
	{
		if($this->match($url)){
			$controller = $this->params['controller'];
			$controller = $this->toStudlyCaps($controller);
			//$controller = "App\Controllers\\$controller";
			$controller = $this->getNamespace().$controller;

			if(class_exists($controller)){
				$controller_object = new $controller($this->params);

				$action = $this->params['action'];
				$action = $this->toCamelCase($action);

				if(is_callable([$controller_object,$action])){
					$controller_object->$action();
				}else{
					//echo "Method $action in controller $controller not found";
					throw new \Exception("Method $action in controller $controller not found");
					
				}
			}else{
				//echo "controller $controller not found";
				throw new \Exception("controller $controller not found");
				
			}
		}else{
			//echo "No route matched";
			throw new \Exception("No route matched $url",404);
			
		}
	}

	/**
	 * @des Convert the string with hyphens '-' to StudlyCaps
	 * @param $string 
	 * @return converted StudlyCaps string
	 */
	protected function toStudlyCaps($string)
	{
		return str_replace(' ','', ucwords(str_replace('-',' ',$string)));
	}


	/**
	 * @des Convert the string with hyphens '-' to camelCase
	 * @param $string
	 * @return converted camelCase string
	 */

	protected function toCamelCase($string)
	{
		return lcfirst($this->toStudlyCaps($string));
	}

	/**
	* @des remove Query String Variables
	* @param $url
	*/
	protected function removeQueryStringVariables($url)
	{
		if($url != '')
		{
			$parts = explode('&',$url,2);

			if(strpos($parts[0],'=') === false){
				$url = $parts[0];
			}else{
				$url = '';
			}
		}

		return $url;
	}

	// get routes 
	public function getRoutes()
	{
		return $this->routers; 
	}

	// get params
	public function getParams()
	{
		return $this->params;
	}

	// get namespace
	protected function getNamespace()
	{
		$namespace = 'App\Controllers\\';

		if (array_key_exists('namespace',$this->params)) {
			$namespace .= $this->params['namespace'] . '\\';

		}

		return $namespace;
	}


}




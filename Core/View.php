<?php
/****************************************************/
// Filename: View.php
// Created: Lisheng Liu
/****************************************************/

namespace Core;

/**
 * View
 */


/**
 * Render a view file
 */
class View
{
	/**
	 * Render a view using Twig
	 */

	public static function renderTemplate($template, $arguments = [] )
	{
		static $twig = null;

		if($twig === null)
		{
			$loader = new \Twig_Loader_Filesystem('../App/Views');
			$twig = new \Twig_Environment($loader);
		}
		echo $twig->render($template,$arguments);
	}
}
?>
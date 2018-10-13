<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
		$router[] = $apiList =  new RouteList('RemoteApi');
		$apiList[] = new Route('api/<action=default>', [
			'presenter' => 'Data',
		]);

		$router[] = new Route('/<action>', 'Homepage:default');
		return $router;
	}
}

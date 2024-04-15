<?php

const APP_VERSION = '1.0.0-RC3';


// init session
$secure = false;
if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
	$secure = true;
	header('strict-transport-security: max-age=15552000; includeSubDomains');
}
$session_length_seconds = $settings['sessionTimeout'];
ini_set('session.gc_maxlifetime', $session_length_seconds);
session_set_cookie_params([
	'lifetime' => 0, // until browser closed (or session purged by PHP's gc_maxlifetime)
	'path' => $settings['baseDir'],
	'domain' => null,
	'secure' => $secure,
	'httponly' => true,
	'samesite' => 'Strict'
]);
session_name('VAULTSESSID');
session_start();


$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->addDefinitions([
	'settings' => $settings,
	'view' => function ($container) {
		global $settings;
		$view = \Slim\Views\Twig::create(__DIR__.'/Views', [
			'cache' => $settings['templateCache']
		]);
		$langCtrl = new XVault\Controllers\LanguageController();
		$view->addExtension(new XVault\Twig_Extensions\TranslateFilterExtension($langCtrl));
		return $view;
	},
	XVault\Controllers\RedirectController::class => function ($containerInterface) {
		$settings = $containerInterface->get('settings');
		return $settings;
	},
]);

\Slim\Factory\AppFactory::setContainer($containerBuilder->build());
$app = \Slim\Factory\AppFactory::create();
$app->setBasePath($settings['baseDir']);
$app->addRoutingMiddleware();


$container = $app->getContainer();
$container->set(XVault\Controllers\UserController::class, function($container) {
	return new XVault\Controllers\UserController($container);
});
$container->set(XVault\Controllers\VaultController::class, function($container) {
	return new XVault\Controllers\VaultController($container);
});

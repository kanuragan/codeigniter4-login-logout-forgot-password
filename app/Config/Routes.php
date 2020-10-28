<?php namespace Config;


$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->group('auth', function($routes){
	$routes->get('/','User/Auth::index');
	$routes->post('login','User/Auth::check_validation', ['as'=>'auth_login']);
	$routes->add('logout','User/Auth::logout', ['as'=>'auth_logout']);
	$routes->add('forgot','User/Auth::forgot_password');
	$routes->get('reset_password/(:any)/(:any)','User/Auth::reset_password');
	$routes->post('reset_password','User/Auth::update_password');
});
$routes->group('email', function($routes){
	$routes->add('send_reset','User/Email::send_verification');
});
$routes->group('dashboard', ['filter' => 'Auth'] , function($routes){
	$routes->get('/','Dashboard/Dashboard::index');
});

$routes->get('/', 'User\User::index');

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

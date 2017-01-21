<?php
/**
 * index.php
 *
 * This file is executed for each request
 */
ini_set("soap.wsdl_cache_enabled", 1);  //WSDL file definition cache activation
ini_set('default_socket_timeout', 25);  //Socket timeout : 25 seconds

ignore_user_abort(true);

/** @var \Base $f3 Global F3 variable [\Base](http://fatfreeframework.com/base).en base de données. */
//Autoloader composer
require('../vendor/autoload.php');
$f3 = \Base::instance();

if($f3->exists('SERVER.HTTP_USER_AGENT')) ini_set('user_agent', $f3->get('SERVER.HTTP_USER_AGENT')); //User agent definition (Necessary to avoid FOPEN errors)

//Main application folder
$f3->set('MAIN_ROOTPATH', realpath(dirname(__FILE__).'/..'));
//F3 folder
$f3->set('MAIN_APPPATH', realpath(dirname(__FILE__).'/../app'));

//Main configuration files loading
$f3->config($f3->get('MAIN_APPPATH').'/hello/configs/hello.ini');
$f3->config($f3->get('MAIN_APPPATH').'/hello/configs/routes.ini');
\Base::instance()->set('PLUGINS', \Base::instance()->get('MAIN_APPPATH').'/');

$f3->run();

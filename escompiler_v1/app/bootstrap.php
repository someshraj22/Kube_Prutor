<?php

// Load environment variables.

$envvars = explode("\n", file_get_contents('/etc/environment'));

foreach($envvars as $env) {
	if(!$env) continue;
	$pair = explode("=", $env);
	$_SERVER[$pair[0]] = $pair[1];
}

// Bootstrap the application.

require_once('vendor/rb.php');
require_once('module.php');

$db_host=$_SERVER['DB_HOST'];
$db_port=$_SERVER['DB_PORT'];
$db_user=$_SERVER['DB_USER'];
$db_pass=$_SERVER['DB_PASS'];
$db_name=$_SERVER['DB_NAME'];

R::setup(sprintf('mysql:host=%s;port=%s;dbname=%s',$db_host,$db_port,$db_name),$db_user,$db_pass);

?>
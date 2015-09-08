<?php
require 'vendor/autoload.php';
require 'db.php';

use Simplon\Mysql\Mysql;

//time for sleep
$t = 5;

$dbConn = new Mysql(
	$config['host'],
    $config['user'],
    $config['password'],
    $config['database']
);

$sqlManager = new \Simplon\Mysql\Manager\SqlManager($dbConn);
<?php

return new \Cubes\Nestpay\PaymentDaoPdo(new \PDO('mysql:host=db;dbname=nestpay', 'root', 'root', [
	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]));
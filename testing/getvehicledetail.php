<?php

require 'index.php';

use Ay4t\GPSID\Command\VehicleDetail;


$client = new VehicleDetail;
$client->setUsername('username');
$client->setPassword('password');
$client->setImeiNumber(123123123);

$get_data   = $client->get();

var_dump($get_data);
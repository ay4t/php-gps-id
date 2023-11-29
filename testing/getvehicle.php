<?php

require 'index.php';

use Ay4t\GPSID\Command\Vehicle;


$client = new Vehicle;
$client->setUsername('username');
$client->setPassword('password');

$get_data   = $client->get();

var_dump($get_data);
<?php

require 'index.php';

use Ay4t\GPSID\Command\Vehicle;


$client = new Vehicle;
$get_data   = $client->get();

var_dump($get_data);
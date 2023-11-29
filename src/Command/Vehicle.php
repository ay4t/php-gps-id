<?php

namespace Ay4t\GPSID\Command;
use Ay4t\GPSID\Client;

class Vehicle extends Client
{
    protected bool $auth         = true;
    protected string $endpoint     = '/vehicle';

    public function get()
    {
        return $this->exec( 'GET', $this->endpoint );
    }

}

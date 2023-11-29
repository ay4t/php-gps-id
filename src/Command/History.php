<?php

namespace Ay4t\GPSID\Command;
use Ay4t\GPSID\Client;

class History extends Client
{
    
    protected bool $auth            = true;
    protected string $endpoint     = '/report/history';

    public function get()
    {
        return $this->exec( 'GET', $this->endpoint );
    }
}

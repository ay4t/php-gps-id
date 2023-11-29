<?php

namespace Ay4t\GPSID\Command;
use Ay4t\GPSID\Client;

class VehicleDetail extends Client
{
    protected bool $auth        = true;
    protected int $imei_number;
    protected string $endpoint  = '/vehicle/detail/';

    public function get()
    {
        // jika this imei_number kosong throw error
        if( empty($this->imei_number) ){
            throw new \Exception("IMEI Number is required");
        }

        return $this->exec( 'GET', $this->endpoint . $this->imei_number );
    }

    /**
     * Set the value of imei_number
     *
     * @param int $imei_number
     *
     * @return self
     */
    public function setImeiNumber(int $imei_number): self {
        $this->imei_number = $imei_number;
        return $this;
    }
}

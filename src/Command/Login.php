<?php

namespace Ay4t\GPSID\Command;
use Ay4t\GPSID\Client;

class Login extends Client
{
    
        public function __construct( string $token = null )
        {
            parent::__construct( $token );
        }
    
        public function process(  string $username = null , string $password = null )
        {
            if( $username ){
                $this->username = $username;
            }
    
            if( $password ){
                $this->password = $password;
            }
    
            return $this->exec( 'POST', '/login', [
                'username' => $this->username,
                'password' => $this->password,
            ]);
            
        }
}

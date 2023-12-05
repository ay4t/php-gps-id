<?php

namespace Ay4t\GPSID;

use Ay4t\GPSID\Command\Login;
use Ay4t\GPSID\Config\Auth;

class Client
{

    /**
     * @var \Ay4t\GPSID\Config\Auth
     */
    protected $config;
    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @var string
     */
    protected string $token;

    /**
     * @var bool
     */
    protected bool $auth = false;

    /**
     * @var string
     */
    protected string $base_url = 'https://portal.gps.id/backend/seen/public';

    /**
     * @var string
     */
    protected string $endpoint = '';

    /**
     * @var array
     */
    protected $result;


    public function __construct( string $token = null )
    {
        // set date timezone to Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $this->config = new Auth();

        $this->username     = ($this->config->username) ? $this->config->username : '';
        $this->password     = ($this->config->password) ? $this->config->password : '';
        if( $token ){            
            $this->token = $token;
        } 
    }

    /**
     * Melakukan perintah request kedalam endpoint dengan GuzzleHttp
     *
     * @param string $method
     * @param string $endpoint
     * @param array $params
     */
    protected function exec( string $method = 'GET', string $endpoint, array $param_body = [] )
    {
        $client             = new \GuzzleHttp\Client();
        $this->endpoint     = $endpoint;
        $url                = $this->base_url . $this->endpoint;

        if( $this->auth ){

            // jika terdapat file Config/token.txt maka ambil token dari file tersebut
            if( file_exists( __DIR__ . '/Config/token.txt' ) ){
                $token_file     = fopen( __DIR__ . '/Config/token.txt', 'r' );
                $token_file_data    = fread( $token_file, filesize( __DIR__ . '/Config/token.txt' ) );

                $token_file_data    = json_decode( $token_file_data, true );
                $this->token        = $token_file_data['token'];

                // jika token sudah expired maka lakukan login ulang
                if( strtotime( $token_file_data['expired'] ) < strtotime( date('Y-m-d H:i:s') ) ){
                    $login_data     = $this->login( $this->username, $this->password );
                }

            } else {
                $login_data     = $this->login( $this->username, $this->password );
            }

            $params['headers'] = [
                'Authorization' => 'Bearer ' . $this->token,
            ];
        }

        $params['form_params'] = $param_body;
        
        try {
            $request    = $client->request( $method, $url, $params );            
            $request_result     = $request->getBody()->getContents();
            $request_result     = json_decode( $request_result, true );
            
            // jika request_result status code bukan 200 maka tampilkan pesan error nya
            if( $request->getStatusCode() == 200 ){
                $this->result   = [
                    'status'    => $request_result['status'],
                    'data'      => $request_result['message']['data'],
                ];
            }

        } catch (\Throwable $th) {
            $this->result   = [
                'status'    => false,
                'data'      => $th->getMessage(),
            ];
        }

        return $this->result;
    }

    private function login(string $username = null , string $password = null)
    {
        $username   = ( $username ) ? $username : $this->username;
        $password   = ( $password ) ? $password : $this->password;

        $login  = new Login();
        $result = $login->process( $username, $password );

        if( ! empty($result['data']['token']) ){
            $this->token = $result['data']['token'];

            // expired token dalam 2 jam
            $expired_time   = date('Y-m-d H:i:s', strtotime('+2 hours'));

            // write json token data to file
            $write_json_data    = [
                'token'     => $this->token,
                'expired'   => $expired_time
            ];

            // simpan data token kedalam file Config/token.txt
            $token_file = fopen( __DIR__ . '/Config/token.txt', 'w' );
            fwrite( $token_file, json_encode( $write_json_data ) );
        } else {
            throw new \Exception("Invalid login credentials");  
        }

        return $this;
    }

    /**
     * Set the value of username
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self {
        $this->username = $username;
        return $this;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    /**
     * Set the value of token
     *
     * @param string $token
     *
     * @return self
     */
    public function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    /**
     * Get the value of result
     *
     * @return string
     */
    public function getResult(): string {
        return ( isset($this->result['data']) ) ? $this->result['data'] : $this->result;
    }
}

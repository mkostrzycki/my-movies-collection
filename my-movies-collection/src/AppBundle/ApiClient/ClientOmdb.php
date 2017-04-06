<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 06.04.2017
 * Time: 10:48
 */

namespace AppBundle\ApiClient;

use GuzzleHttp\Client;

class ClientOmdb extends Client
{
    private $api;

    public function __construct()
    {
        parent::__construct();

        /** @var Client $client */
        $this->api = $this->get('guzzle.client.api_omdb');
    }
}
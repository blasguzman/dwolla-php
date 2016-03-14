<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class MockHttpClient
{

    public function __construct() {
        $this->container = [];
        $mock = new MockHandler([new Response(200, [])]);
        $history = Middleware::history($this->container);
        $stack = HandlerStack::create($mock);
        $stack->push($history);
        $this->client = new Client(['handler' => $stack]);
    }

    public function getClient() {
        return $this->client;
    }

    public function getLastRequest() {
        return $this->container[0]['request'];
    }

    public function getLastPath() {
        return $this->getLastRequest()->getUri()->getPath();
    }

    public function getLastOauthToken() {
        return $this->getPartFromLastQuery('oauth_token');
    }

    public function getLastClientId() {
        return $this->getPartFromLastQuery('client_id');
    }

    public function getLastClientSecret() {
        return $this->getPartFromLastQuery('client_secret');
    }

    public function getLastBody() {
        return json_decode($this->getLastRequest()->getBody(), true);
    }

    public function getParamFromLastBody($param) {
       return $this->getLastBody()[$param];
    }

    public function getPartFromLastQuery($key) {
         foreach (explode('&', $this->getLastRequest()->getUri()->getQuery()) as $part) {
             $value = explode('=', $part);
             if ($value[0] == $key) {
                return urldecode($value[1]);
             };
         }
    }
}

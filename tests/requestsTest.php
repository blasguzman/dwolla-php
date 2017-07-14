<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\Requests;

class RequestsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->Requests = new Requests();
        $this->mock_client = new MockHttpClient();
        $this->Requests->client = $this->mock_client->getClient();
    }

    public function testCreate() {
        $this->Requests->create('812-111-1111', 5.00);

        $this->assertEquals('/oauth/rest/requests/', $this->mock_client->getLastPath());

        $this->assertEquals($this->Requests->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals('812-111-1111', $this->mock_client->getParamFromLastBody('sourceId'));
        $this->assertEquals(5.00, $this->mock_client->getParamFromLastBody('amount'));
    }

    public function testGet() {
        $this->Requests->get();

        $this->assertEquals('/oauth/rest/requests', $this->mock_client->getLastPath());
        $this->assertEquals($this->Requests->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testInfo() {
        $this->Requests->info('12345678');

        $this->assertEquals('/oauth/rest/requests/12345678', $this->mock_client->getLastPath());
        $this->assertEquals($this->Requests->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testCancel() {
        $this->Requests->cancel('12345678');

        $this->assertEquals('/oauth/rest/requests/12345678/cancel', $this->mock_client->getLastPath());
        $this->assertEquals($this->Requests->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testFulfill() {
        $this->Requests->fulfill('12345678', 7.50);

        $this->assertEquals('/oauth/rest/requests/12345678/fulfill', $this->mock_client->getLastPath());

        $this->assertEquals($this->Requests->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals(7.50, $this->mock_client->getParamFromLastBody('amount'));
    }
}

<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\MassPay;

class MassPayTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->MassPay = new MassPay();
        $this->mock_client = new MockHttpClient();
        $this->MassPay->client = $this->mock_client->getClient();
    }

    public function testCreate() {
        $this->MassPay->create('123456', [ 'unit' => 'test' ]);

        $this->assertEquals('/oauth/rest/masspay/', $this->mock_client->getLastPath());

        $this->assertEquals($this->MassPay->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->MassPay->settings->pin, $this->mock_client->getParamFromLastBody('pin'));
        $this->assertEquals('123456', $this->mock_client->getParamFromLastBody('fundsSource'));
        $this->assertEquals([ 'unit' => 'test' ], $this->mock_client->getParamFromLastBody('items'));
    }

    public function testGetJob() {
        $this->MassPay->getJob('123456');

        $this->assertEquals('/oauth/rest/masspay/123456', $this->mock_client->getLastPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testGetJobItems() {
        $this->MassPay->getJobItems('123456');

        $this->assertEquals('/oauth/rest/masspay/123456/items', $this->mock_client->getLastPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testGetItem() {
        $this->MassPay->getItem('123456', '654321');

        $this->assertEquals('/oauth/rest/masspay/123456/items/654321', $this->mock_client->getLastPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testListJobs() {
        $this->MassPay->listJobs();

        $this->assertEquals('/oauth/rest/masspay/', $this->mock_client->getLastPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }
}

<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\OAuth;

class OAuthTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->OAuth = new OAuth();
        $this->mock_client = new MockHttpClient();
        $this->OAuth->client = $this->mock_client->getClient();
    }

    public function testGenAuthUrl() {
        $this->assertEquals($this->OAuth->settings->host . 'oauth/v2/authenticate?client_id='
                                                         . urlencode($this->OAuth->settings->client_id)
                                                         . '&response_type=code&scope='
                                                         . urlencode($this->OAuth->settings->oauth_scope)
                                                         , $this->OAuth->genAuthUrl());
    }

    public function testGet() {
        $this->OAuth->get('ABCDEF');

        $this->assertEquals('/oauth/v2/token', $this->mock_client->getLastPath());
        $this->assertEquals($this->OAuth->settings->client_id, $this->mock_client->getParamFromLastBody('client_id'));
        $this->assertEquals($this->OAuth->settings->client_secret, $this->mock_client->getParamFromLastBody('client_secret'));
        $this->assertEquals('authorization_code', $this->mock_client->getParamFromLastBody('grant_type'));
        $this->assertEquals('ABCDEF', $this->mock_client->getParamFromLastBody('code'));
    }

    public function testRefresh() {
        $this->OAuth->refresh('ABCDEF');

        $this->assertEquals('/oauth/v2/token', $this->mock_client->getLastPath());
        $this->assertEquals($this->OAuth->settings->client_id, $this->mock_client->getParamFromLastBody('client_id'));
        $this->assertEquals($this->OAuth->settings->client_secret, $this->mock_client->getParamFromLastBody('client_secret'));
        $this->assertEquals('refresh_token', $this->mock_client->getParamFromLastBody('grant_type'));
        $this->assertEquals('ABCDEF', $this->mock_client->getParamFromLastBody('refresh_token'));
    }

    public function testCatalog() {

        $this->OAuth->catalog('Catalog Test Token');

        $this->assertEquals('/oauth/rest/catalog', $this->mock_client->getLastPath());
        $this->assertEquals('Catalog Test Token', $this->mock_client->getLastOauthToken());
    }
}

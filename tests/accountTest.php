<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\Account;

class AccountTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->Account = new Account();
        $this->mock_client = new MockHttpClient();
        $this->Account->client = $this->mock_client->getClient();
    }

    public function testBasic() {
        $this->Account->basic('812-111-1111');

        $this->assertEquals('/oauth/rest/users/812-111-1111', $this->mock_client->getLastPath());

        $this->assertEquals($this->Account->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Account->settings->client_secret, $this->mock_client->getLastClientSecret());
    }

    public function testFull() {
        $this->Account->full();

        $this->assertEquals('/oauth/rest/users/', $this->mock_client->getLastPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testFullWithOverride() {
        $this->Account->full('TEST OVERRIDE TOKEN');

        $this->assertEquals('/oauth/rest/users/', $this->mock_client->getLastPath());
        $this->assertEquals('TEST OVERRIDE TOKEN', $this->mock_client->getLastOauthToken());
    }

    public function testBalance() {
        $this->Account->balance();

        $this->assertEquals('/oauth/rest/balance/', $this->mock_client->getLastPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testNearby() {
        $this->Account->nearby(45, 50);

        $this->assertEquals('/oauth/rest/users/nearby', $this->mock_client->getLastPath());

        $this->assertEquals($this->Account->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Account->settings->client_secret, $this->mock_client->getLastClientSecret());
        $this->assertEquals(45, $this->mock_client->getPartFromLastQuery('latitude'));
        $this->assertEquals(50, $this->mock_client->getPartFromLastQuery('longitude'));
    }

    public function testAWStatus() {
        $this->Account->getAutoWithdrawalStatus();

        $this->assertEquals('/oauth/rest/accounts/features/auto_withdrawl', $this->mock_client->getLastPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testAWToggle() {
        $this->Account->toggleAutoWithdrawalStatus(true, '12345678');

        $this->assertEquals('/oauth/rest/accounts/features/auto_withdrawl', $this->mock_client->getLastPath());

        $this->assertEquals($this->Account->settings->oauth_token, $this->mock_client->getParamFromLastBody('oauth_token'));
        $this->assertEquals(1, $this->mock_client->getParamFromLastBody('enabled'));
        $this->assertEquals('12345678', $this->mock_client->getParamFromLastBody('fundingId'));
    }
}

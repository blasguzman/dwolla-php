<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\fundingSources;

class fundingSourcesTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->fS = new fundingSources();
        $this->mock_client = new MockHttpClient();
        $this->fS->client = $this->mock_client->getClient();
    }

    public function testInfo() {
        $this->fS->info('12345678');

        $this->assertEquals('/oauth/rest/fundingsources/12345678', $this->mock_client->getLastPath());
        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testGet() {
        $this->fS->get();

        $this->assertEquals('/oauth/rest/fundingsources', $this->mock_client->getLastPath());
        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testAdd() {
        $this->fS->add('123456', '654321', 'Unit', 'Testing');

        $this->assertEquals('/oauth/rest/fundingsources/', $this->mock_client->getLastPath());

        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals('123456', $this->mock_client->getParamFromLastBody('account_number'));
        $this->assertEquals('654321', $this->mock_client->getParamFromLastBody('routing_number'));
        $this->assertEquals('Unit', $this->mock_client->getParamFromLastBody('account_type'));
        $this->assertEquals('Testing', $this->mock_client->getParamFromLastBody('name'));
    }

    public function testVerify() {
        $this->fS->verify(0.04, 0.07, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/verify', $this->mock_client->getLastPath());

        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals('0.04', $this->mock_client->getParamFromLastBody('deposit1'));
        $this->assertEquals('0.07', $this->mock_client->getParamFromLastBody('deposit2'));
    }

    public function testWithdraw() {
        $this->fS->withdraw(10, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/withdraw', $this->mock_client->getLastPath());

        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->fS->settings->pin, $this->mock_client->getParamFromLastBody('pin'));
        $this->assertEquals('10', $this->mock_client->getParamFromLastBody('amount'));
    }

    public function testDeposit() {
        $this->fS->deposit(15, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/deposit', $this->mock_client->getLastPath());

        $this->assertEquals($this->fS->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->fS->settings->pin, $this->mock_client->getParamFromLastBody('pin'));
        $this->assertEquals('15', $this->mock_client->getParamFromLastBody('amount'));
    }
}

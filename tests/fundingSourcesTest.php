<?php

require_once('../vendor/autoload.php');

use Dwolla\fundingSources;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class fundingSourcesTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);

        $this->fS = new fundingSources();
        $this->history = new History();

        $this->fS->client->getEmitter()->attach($this->history);
    }

    public function testInfo() {
        $this->fS->info('12345678');

        $this->assertEquals('/oauth/rest/fundingsources/12345678', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->fS->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testGet() {
        $this->fS->get();

        $this->assertEquals('/oauth/rest/fundingsources', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->fS->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testAdd() {
        $this->fS->add('123456', '654321', 'Unit', 'Testing');

        $this->assertEquals('/oauth/rest/fundingsources/', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->fS->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals('123456', json_decode($this->history->getLastRequest()->getBody(), true)['account_number']);
        $this->assertEquals('654321', json_decode($this->history->getLastRequest()->getBody(), true)['routing_number']);
        $this->assertEquals('Unit', json_decode($this->history->getLastRequest()->getBody(), true)['account_type']);
        $this->assertEquals('Testing', json_decode($this->history->getLastRequest()->getBody(), true)['name']);
    }

    public function testVerify() {
        $this->fS->verify(0.04, 0.07, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/verify', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->fS->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals('0.04', json_decode($this->history->getLastRequest()->getBody(), true)['deposit1']);
        $this->assertEquals('0.07', json_decode($this->history->getLastRequest()->getBody(), true)['deposit2']);
    }

    public function testWithdraw() {
        $this->fS->withdraw(10, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/withdraw', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->fS->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals($this->fS->settings->pin, json_decode($this->history->getLastRequest()->getBody(), true)['pin']);
        $this->assertEquals('10', json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }

    public function testDeposit() {
        $this->fS->deposit(15, '123456');

        $this->assertEquals('/oauth/rest/fundingsources/123456/deposit', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->fS->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals($this->fS->settings->pin, json_decode($this->history->getLastRequest()->getBody(), true)['pin']);
        $this->assertEquals('15', json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }
}
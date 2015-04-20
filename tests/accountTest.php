<?php

require_once('../vendor/autoload.php');

use Dwolla\Account;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class AccountTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);

        $this->Account = new Account();
        $this->history = new History();

        $this->Account->client->getEmitter()->attach($this->history);
    }

    public function testBasic() {
        $this->Account->basic('812-111-1111');

        $this->assertEquals('/oauth/rest/users/812-111-1111', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Account->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->Account->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
    }

    public function testFull() {
        $this->Account->full();

        $this->assertEquals('/oauth/rest/users/', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testFullWithOverride() {
        $this->Account->full('TEST OVERRIDE TOKEN');

        $this->assertEquals('/oauth/rest/users/', $this->history->getLastRequest()->getPath());
        $this->assertEquals('TEST OVERRIDE TOKEN', $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testBalance() {
        $this->Account->balance();

        $this->assertEquals('/oauth/rest/balance/', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testNearby() {
        $this->Account->nearby(45, 50);

        $this->assertEquals('/oauth/rest/users/nearby', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Account->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->Account->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
        $this->assertEquals(45, $this->history->getLastRequest()->getQuery()['latitude']);
        $this->assertEquals(50, $this->history->getLastRequest()->getQuery()['longitude']);
    }

    public function testAWStatus() {
        $this->Account->getAutoWithdrawalStatus();

        $this->assertEquals('/oauth/rest/accounts/features/auto_withdrawl', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Account->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testAWToggle() {
        $this->Account->toggleAutoWithdrawalStatus(true, '12345678');

        $this->assertEquals('/oauth/rest/accounts/features/auto_withdrawl', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Account->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals(1, json_decode($this->history->getLastRequest()->getBody(), true)['enabled']);
        $this->assertEquals('12345678', json_decode($this->history->getLastRequest()->getBody(), true)['fundingId']);
    }

}
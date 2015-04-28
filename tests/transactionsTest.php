<?php

require_once('../vendor/autoload.php');

use Dwolla\Transactions;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class TransactionsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        
        $this->Transactions = new Transactions();
        $this->history = new History();

        $this->Transactions->client->getEmitter()->attach($this->history);
    }

    public function testSend() {
        $this->Transactions->send('812-111-1111', 5);

        $this->assertEquals('/oauth/rest/transactions/send', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals('812-111-1111', json_decode($this->history->getLastRequest()->getBody(), true)['destinationId']);
        $this->assertEquals(5, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }

    public function testGet() {
        $this->Transactions->get();

        $this->assertEquals('/oauth/rest/transactions', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
        $this->assertEquals($this->Transactions->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->Transactions->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
    }

    public function testInfo() {
        $this->Transactions->info('123456');

        $this->assertEquals('/oauth/rest/transactions/123456', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
        $this->assertEquals($this->Transactions->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->Transactions->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
    }

    public function testRefund() {
        $this->Transactions->refund('12345', 'Balance', 5.50);

        $this->assertEquals('/oauth/rest/transactions/refund', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals($this->Transactions->settings->pin, json_decode($this->history->getLastRequest()->getBody(), true)['pin']);
        $this->assertEquals('12345', json_decode($this->history->getLastRequest()->getBody(), true)['transactionId']);
	$this->assertEquals('Balance', json_decode($this->history->getLastRequest()->getBody(), true)['fundsSource']);
        $this->assertEquals(5.50, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }

    public function testStats() {
        $this->Transactions->stats();

        $this->assertEquals('/oauth/rest/transactions/stats', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testSchedule() {
        $this->Transactions->schedule('812-111-1111', 5, '2051-01-01', 'ashfdjh8f9df89');

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals('812-111-1111', json_decode($this->history->getLastRequest()->getBody(), true)['destinationId']);
        $this->assertEquals(5, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
        $this->assertEquals('2051-01-01', json_decode($this->history->getLastRequest()->getBody(), true)['scheduleDate']);
        $this->assertEquals('ashfdjh8f9df89', json_decode($this->history->getLastRequest()->getBody(), true)['fundsSource']);
    }

    public function testScheduled() {
        $this->Transactions->scheduled();

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);       
    }

    public function testScheduledById() {
        $this->Transactions->scheduledById('anid');

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testEditScheduled() {
        $this->Transactions->editScheduled('anid', ['amount' => 5.50]);

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals(5.50, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }

    public function testDeleteScheduledById() {
        $this->Transactions->deleteScheduledById('anid');

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testDeleteAllScheduled() {
        $this->Transactions->deleteAllScheduled();

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

}

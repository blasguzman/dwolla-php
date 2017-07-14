<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\Transactions;

class TransactionsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->Transactions = new Transactions();
        $this->mock_client = new MockHttpClient();
        $this->Transactions->client = $this->mock_client->getClient();
    }

    public function testSend() {
        $this->Transactions->send('812-111-1111', 5);

        $this->assertEquals('/oauth/rest/transactions/send', $this->mock_client->getLastPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals('812-111-1111', $this->mock_client->getParamFromLastBody('destinationId'));
        $this->assertEquals(5, $this->mock_client->getParamFromLastBody('amount'));
    }

    public function testGet() {
        $this->Transactions->get();

        $this->assertEquals('/oauth/rest/transactions', $this->mock_client->getLastPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->Transactions->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Transactions->settings->client_secret, $this->mock_client->getLastClientSecret());
    }

    public function testInfo() {
        $this->Transactions->info('123456');

        $this->assertEquals('/oauth/rest/transactions/123456', $this->mock_client->getLastPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->Transactions->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Transactions->settings->client_secret, $this->mock_client->getLastClientSecret());
    }

    public function testRefund() {
        $this->Transactions->refund('12345', 'Balance', 5.50);

        $this->assertEquals('/oauth/rest/transactions/refund', $this->mock_client->getLastPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals($this->Transactions->settings->pin, $this->mock_client->getParamFromLastBody('pin'));
        $this->assertEquals('12345', $this->mock_client->getParamFromLastBody('transactionId'));
        $this->assertEquals('Balance', $this->mock_client->getParamFromLastBody('fundsSource'));
        $this->assertEquals(5.50, $this->mock_client->getParamFromLastBody('amount'));
    }

    public function testStats() {
        $this->Transactions->stats();

        $this->assertEquals('/oauth/rest/transactions/stats', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testSchedule() {
        $this->Transactions->schedule('812-111-1111', 5, '2051-01-01', 'ashfdjh8f9df89');

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->mock_client->getLastPath());

        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals('812-111-1111', $this->mock_client->getParamFromLastBody('destinationId'));
        $this->assertEquals(5, $this->mock_client->getParamFromLastBody('amount'));
        $this->assertEquals('2051-01-01', $this->mock_client->getParamFromLastBody('scheduleDate'));
        $this->assertEquals('ashfdjh8f9df89', $this->mock_client->getParamFromLastBody('fundsSource'));
    }

    public function testScheduled() {
        $this->Transactions->scheduled();

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testScheduledById() {
        $this->Transactions->scheduledById('anid');

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testEditScheduled() {
        $this->Transactions->editScheduled('anid', ['amount' => 5.50]);

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
        $this->assertEquals(5.50, $this->mock_client->getParamFromLastBody('amount'));
    }

    public function testDeleteScheduledById() {
        $this->Transactions->deleteScheduledById('anid');

        $this->assertEquals('/oauth/rest/transactions/scheduled/anid', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testDeleteAllScheduled() {
        $this->Transactions->deleteAllScheduled();

        $this->assertEquals('/oauth/rest/transactions/scheduled', $this->mock_client->getLastPath());
        $this->assertEquals($this->Transactions->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }
}

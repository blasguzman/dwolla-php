<?php

require_once('../vendor/autoload.php');

use Dwolla\Requests;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class RequestsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);

        $this->Requests = new Requests();
        $this->history = new History();

        $this->Requests->client->getEmitter()->attach($this->history);
    }

    public function testCreate() {
        $this->Requests->create('812-111-1111', 5.00);

        $this->assertEquals('/oauth/rest/requests/', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Requests->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals('812-111-1111', json_decode($this->history->getLastRequest()->getBody(), true)['sourceId']);
        $this->assertEquals(5.00, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }

    public function testGet() {
        $this->Requests->get();

        $this->assertEquals('/oauth/rest/requests', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Requests->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testInfo() {
        $this->Requests->info('12345678');

        $this->assertEquals('/oauth/rest/requests/12345678', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Requests->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testCancel() {
        $this->Requests->cancel('12345678');

        $this->assertEquals('/oauth/rest/requests/12345678/cancel', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Requests->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
    }

    public function testFulfill() {
        $this->Requests->fulfill('12345678', 7.50);

        $this->assertEquals('/oauth/rest/requests/12345678/fulfill', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Requests->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals(7.50, json_decode($this->history->getLastRequest()->getBody(), true)['amount']);
    }
}
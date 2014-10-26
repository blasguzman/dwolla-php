<?php

require_once('../vendor/autoload.php');

use Dwolla\MassPay;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class MassPayTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);

        $this->MassPay = new MassPay();
        $this->history = new History();

        $this->MassPay->client->getEmitter()->attach($this->history);
    }

    public function testCreate() {
        $this->MassPay->create('123456', [ 'unit' => 'test' ]);

        $this->assertEquals('/oauth/rest/masspay/', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->MassPay->settings->oauth_token, json_decode($this->history->getLastRequest()->getBody(), true)['oauth_token']);
        $this->assertEquals($this->MassPay->settings->pin, json_decode($this->history->getLastRequest()->getBody(), true)['pin']);
        $this->assertEquals('123456', json_decode($this->history->getLastRequest()->getBody(), true)['fundsSource']);
        $this->assertEquals([ 'unit' => 'test' ], json_decode($this->history->getLastRequest()->getBody(), true)['items']);
    }

    public function testGetJob() {
        $this->MassPay->getJob('123456');

        $this->assertEquals('/oauth/rest/masspay/123456', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testGetJobItems() {
        $this->MassPay->getJobItems('123456');

        $this->assertEquals('/oauth/rest/masspay/123456/items', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testGetItem() {
        $this->MassPay->getItem('123456', '654321');

        $this->assertEquals('/oauth/rest/masspay/123456/items/654321', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testListJobs() {
        $this->MassPay->listJobs();

        $this->assertEquals('/oauth/rest/masspay/', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->MassPay->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }
}
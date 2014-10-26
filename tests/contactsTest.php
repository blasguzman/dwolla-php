<?php

require_once('../vendor/autoload.php');

use Dwolla\Contacts;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class ContactsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);

        $this->Contacts = new Contacts();
        $this->history = new History();

        $this->Contacts->client->getEmitter()->attach($this->history);
    }

    public function testGet() {
        $this->Contacts->get();

        $this->assertEquals('/oauth/rest/contacts', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->Contacts->settings->oauth_token, $this->history->getLastRequest()->getQuery()['oauth_token']);
    }

    public function testNearby() {
        $this->Contacts->nearby(45, 55);

        $this->assertEquals('/oauth/rest/contacts/nearby', $this->history->getLastRequest()->getPath());

        $this->assertEquals($this->Contacts->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->Contacts->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
        $this->assertEquals(45, $this->history->getLastRequest()->getQuery()['latitude']);
        $this->assertEquals(55, $this->history->getLastRequest()->getQuery()['longitude']);
    }
}
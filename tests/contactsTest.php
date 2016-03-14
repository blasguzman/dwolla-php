<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\Contacts;

class ContactsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->Contacts = new Contacts();
        $this->mock_client = new MockHttpClient();
        $this->Contacts->client = $this->mock_client->getClient();
    }

    public function testGet() {
        $this->Contacts->get();

        $this->assertEquals('/oauth/rest/contacts', $this->mock_client->getLastPath());
        $this->assertEquals($this->Contacts->settings->oauth_token, $this->mock_client->getLastOauthToken());
    }

    public function testNearby() {
        $this->Contacts->nearby(45, 55);

        $this->assertEquals('/oauth/rest/contacts/nearby', $this->mock_client->getLastPath());

        $this->assertEquals($this->Contacts->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Contacts->settings->client_secret, $this->mock_client->getLastClientSecret());
        $this->assertEquals(45, $this->mock_client->getPartFromLastQuery('latitude'));
        $this->assertEquals(55, $this->mock_client->getPartFromLastQuery('longitude'));
    }
}

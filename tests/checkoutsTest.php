<?php

require_once('../vendor/autoload.php');
require_once('mockHttpClient.php');

use Dwolla\Checkouts;

class CheckoutsTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        // As of 10/26/14 we test against all possible PHP errors.
        error_reporting(-1);
        $this->Checkouts = new Checkouts();
        $this->mock_client = new MockHttpClient();
        $this->Checkouts->client = $this->mock_client->getClient();
    }

    public function testCart() {
        $this->Checkouts->addToCart("Test", "Descr", 5, 1);

        $this->assertEquals([
            'name' => "Test",
            'description' => "Descr",
            'price' => 5,
            'quantity' => 1
        ], $this->Checkouts->cart[0]);

        $this->Checkouts->resetCart();

        $this->assertEquals(false, $this->Checkouts->cart);
    }

    public function testCreate() {
        $this->Checkouts->addToCart("Test", "Descr", 5, 1);

        $this->Checkouts->create(['destinationId' => '812-111-1111']);

        $this->assertEquals('/oauth/rest/offsitegateway/checkouts', $this->mock_client->getLastPath());

        $this->assertEquals([
            'client_id' => $this->Checkouts->settings->client_id,
            'client_secret' => $this->Checkouts->settings->client_secret,
            'purchaseOrder' =>
                                [
                                    'orderItems' => [
                                                        [
                                                            'name' => "Test",
                                                            'description' => "Descr",
                                                            'price' => 5,
                                                            'quantity' => 1
                                                        ]
                                                    ],
                                    'total' => number_format(5, 2),
                                    'destinationId' => '812-111-1111'
                                ]
        ], $this->mock_client->getLastBody());
    }

    public function testGet() {
        $this->Checkouts->get('12345');

        $this->assertEquals('/oauth/rest/offsitegateway/checkouts/12345', $this->mock_client->getLastPath());

        $this->assertEquals($this->Checkouts->settings->client_id, $this->mock_client->getLastClientId());
        $this->assertEquals($this->Checkouts->settings->client_secret, $this->mock_client->getLastClientSecret());
    }

    public function testComplete() {
        $this->Checkouts->complete('54321');

        $this->assertEquals('/oauth/rest/offsitegateway/checkouts/54321/complete', $this->mock_client->getLastPath());

        $this->assertEquals($this->Checkouts->settings->client_id, $this->mock_client->getParamFromLastBody("client_id"));
        $this->assertEquals($this->Checkouts->settings->client_secret, $this->mock_client->getParamFromLastBody("client_secret"));
    }
}

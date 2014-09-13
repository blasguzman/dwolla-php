<?php

require_once('../vendor/autoload.php');

require ('../lib/oauth.php');

use Dwolla\OAuth;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\History;

class OAuthTest extends PHPUnit_Framework_TestCase
{

    public function setUp() {
        $this->OAuth = new OAuth();
        $this->history = new History();

        $this->OAuth->client->getEmitter()->attach($this->history);
    }

    public function testGenAuthUrl() {
        $this->assertEquals($this->OAuth->settings->host . 'oauth/v2/authenticate?client_id=' . urlencode($this->OAuth->settings->client_id) . '&response_type=code&scope=' . urlencode($this->OAuth->settings->oauth_scope), $this->OAuth->genAuthUrl());
    }

    public function testGet() {
        $this->OAuth->get('ABCDEF');

        $this->assertEquals('/oauth/v2/token', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->OAuth->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->OAuth->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
        $this->assertEquals('authorization_code', $this->history->getLastRequest()->getQuery()['grant_type']);
        $this->assertEquals('ABCDEF', $this->history->getLastRequest()->getQuery()['code']);
    }

    public function testRefresh() {
        $this->OAuth->refresh('ABCDEF');

        $this->assertEquals('/oauth/v2/token', $this->history->getLastRequest()->getPath());
        $this->assertEquals($this->OAuth->settings->client_id, $this->history->getLastRequest()->getQuery()['client_id']);
        $this->assertEquals($this->OAuth->settings->client_secret, $this->history->getLastRequest()->getQuery()['client_secret']);
        $this->assertEquals('refresh_token', $this->history->getLastRequest()->getQuery()['grant_type']);
        $this->assertEquals('ABCDEF', $this->history->getLastRequest()->getQuery()['refresh_token']);
    }
}
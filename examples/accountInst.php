<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the Account class,
 * which encapsulates methods for all account endpoints.
 */

// We need the Account class in order to do anything
require '../lib/account.php';
$Account = new Dwolla\Account();

// Let us set our key, secret, and enable sandbox mode
$Account->settings->client_id = "MY CLIENT ID";
$Account->settings->client_secret = "MY CLIENT SECRET";
$Account->settings->sandbox = true;

/**
 * Example 1: Get basic information for
 * a Dwolla user using their Dwolla ID.
 */
print_r($Account->basic('812-121-7199'));

/**
 * Example 2: Get full account information for
 * the Dwolla user associated with the current OAuth token.
 */

print_r($Account->full());

/**
 * Example 3: Get the balance of the account for
 * the Dwolla user associated with the current OAuth token.
 */

print($Account->balance() . "\n");

/**
 * Example 4: Get users near a certain geographical location.
 */

print_r($Account->nearby(40.7127, 74.0059));

/**
 * Example 5: Get the auto-withdrawal status of the user
 * associated with the current OAuth token.
 */

print_r($Account->getAutoWithdrawalStatus());

/**
 * Example 6: Toggle the auto-withdrawal status of an account
 * under the Dwolla user associated with the current OAuth token.
 */

print_r($Account->toggleAutoWithdrawalStatus(true, "12345678"));
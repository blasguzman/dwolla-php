<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the fundingSources class,
 * which encapsulates methods for all funding-source related endpoints.
 */

// We need the fundingSources class in order to do anything
require '../lib/fundingSources.php';
$FS = new Dwolla\fundingSources();

/**
 * Example 1: Get information about a funding ID
 */

print_r($FS->info('12345678'));

/**
 * Example 2: Get a list of funding sources associated
 * with the account under the current OAuth token
 */

print_r($FS->get());

/**
 * Example 3: Add a funding source to the account associated
 * with the current OAuth token.
 *
 * '12345678' is the account number.
 * '00000000' is the routing number.
 * 'Checking' is the account type.
 * 'My Bank' is a user defined account identifier string.
 */

print_r($FS->add('12345678', '00000000', 'Checking', 'My Bank'));

/**
 * Example 4: Verify the newly created account with via
 * the two micro-deposits.
 *
 * '0.04' is the first deposit.
 * '0.02' is the second deposit.
 * '12345678' is the account number.
 */

print_r($FS->verify(0.04, 0.02, '12345678'));

/**
 * Example 5: Withdraw $5 from Dwolla to funding ID
 * '12345678'.
 */

print_r($FS->withdraw(5.00, '12345678'));

/**
 * Example 6: Deposit $10 into Dwolla from funding ID
 * '12345678'.
 */

print_r($FS->deposit(10.00, '12345678'));

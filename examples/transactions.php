<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the Transactions class,
 * which encapsulates methods for all transaction related endpoints.
 */

// We need the Transactions class in order to do anything
require '../lib/transactions.php';
$Transactions = new Dwolla\Transactions();

/**
 * Example 1: Send $5.50 to a Dwolla ID.
 */
print_r($Transactions->send('812-197-4121', 5.50));

/**
 * Example 2: List transactions for the user
 * associated with the current OAuth token.
 */
print_r($Transactions->get());

/**
 * Example 3: Refund $2 from "Balance" from transaction
 * '123456'.
 */
print_r($Transactions->refund('123456', 'Balance', 2.00));

/**
 * Example 4: Get info for transaction ID '123456'.
 */
print_r($Transactions->info('123456'));

/**
 * Example 5: Get transaction statistics for the user
 * associated with the current OAuth token.
 */
print_r($Transactions->stats());

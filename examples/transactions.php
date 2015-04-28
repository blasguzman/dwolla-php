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

/**
 * Example 6: Send $5.50 to a Dwolla ID,
 * on 2018-01-01, from funding source with ID
 * "ashfdjh8f9df89"
 */
print_r($Transactions->schedule('812-197-4121', 5.50, '2018-01-01', 'ashfdjh8f9df89'));

/**
 * Example 7: Get all scheduled transactions
 */
print_r($Transactions->scheduled());

/**
 * Example 8: Get scheduled transaction with ID
 * 'abdgdsfd35353'
 */
print_r($Transactions->scheduledById('abdgdsfd35353'));

/**
 * Example 9: Edit scheduled transaction with ID
 * 'abdgdsfd35353' to reflect amount 20.50
 */
print_r($Transactions->editScheduled('abdgdsfd35353', ['amount' => 20.50]));

/**
 * Example 10: Delete scheduled transaction with ID
 * 'abdgdsfd35353'
 */
print_r($Transactions->deleteScheduledById('abdgdsfd35353'));

/**
 * Example 11: Delete all scheduled transactions
 */
print_r($Transactions->deleteAllScheduled());
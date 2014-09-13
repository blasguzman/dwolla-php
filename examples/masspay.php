<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the MassPay class,
 * which encapsulates methods for all masspay related endpoints.
 */

// We need the OAuth class in order to do anything
require '../lib/masspay.php';
$MassPay = new Dwolla\MassPay();

/**
 * Example 1: Create a MassPay job with two items to
 * the Balance of the user associated with the current
 * OAuth token.
 */

$info = $MassPay->create('Balance',
    [
        [
            'amount' => 1.00,
            'destination' => '812-197-4121',
        ],
        [
            'amount' => 2.00,
            'destination' => '812-174-9528'
        ]
    ]);
print_r($info);

/**
 * Example 2: Get info regarding the MassPay
 * job which you have just created.
 */

print_r($MassPay->getJob($info['Id']));

/**
 * Example 3: Get all the items submitted with the
 * MassPay job which you have just created.
 */

$items = $MassPay->getJobItems($info['Id']);
print_r($items);

/**
 * Example 4: Get information about the 0th item from
 * the MassPay job you just submitted.
 *
 * Note: You do not need to get all items first, I just
 * re-use data for illustrative purposes.
 */

print_r($MassPay->getItem($info['Id'], $items[0]['ItemId']));

/**
 * Example 5: Get all current MassPay jobs for the
 * user associated with the current OAuth token.
 */

print_r($MassPay->listJobs());


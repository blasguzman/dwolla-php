<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the Requests class,
 * which encapsulates methods for all request related endpoints.
 */

// We need the Requests class in order to do anything
require '../lib/requests.php';
$Requests = new Dwolla\Requests();

/**
 * Example 1: Request $5 from 812-740-3809
 */
print_r($Requests->create('812-740-3809', 5.00));

/**
 * Example 2: Get all pending requests from the user
 * associated with the current OAuth token.
 */
print_r($Requests->get());

/**
 * Example 3: Get info regarding a pending money request.
 *
 * (Replace the ID with a valid one)
 */

print_r($Requests->info(1470));

/**
 * Example 4: Cancel a pending money request.
 *
 * (Replace the ID with a valid one)
 */

print_r($Requests->cancel(1470));


/**
 * Example 5: Fulfill a pending money request.
 *
 * (Replace ID(s) with valid ones/valid amounts)
 */

print_r($Requests->fulfill(1475, 10.00));

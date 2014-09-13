<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the Contacts class,
 * which encapsulates methods for all account endpoints.
 */

// We need the Contacts class in order to do anything
require '../lib/contacts.php';
$Account = new Dwolla\Contacts();

/**
 * Example 1: Get the first 10 contacts from the user
 * associated with the current OAuth token.
 */

print_r($Account->get());

/**
 * Example 2: Get the first 2 contacts from the user
 * associated with the current OAuth token.
 */

print_r($Account->get(
    ['limit' => 2
    ])
);

/**
 * Example 3: Get Dwolla spots near NYC's official
 * coordinates.
 */

print_r($Account->nearby(40.7127, 74.0059));
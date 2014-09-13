<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the OAuth class,
 * which encapsulates methods for all access-token related endpoints.
 */

// We need the OAuth class in order to do anything
require '../lib/oauth.php';
$OAuth = new Dwolla\OAuth();

/**
 * Step 1: Generate an OAuth permissions page URL
 * with your application's default set redirect.
 *
 * http://requestb.in is a service that catches
 * redirect responses. Go over to their URL and make
 * your own so that you may conveniently catch the
 * redirect parameters.
 *
 * You can view your responses at:
 * http://requestb.in/[some_id]?inspect
 *
 * If you're feeling dangerous, feel free to simply use
 * http://google.com and manually parse the parameters
 * out yourself. The choice remains yours.
 */

print($OAuth->genAuthUrl("http://requestb.in/19n5szz1"));

/**
 * Step 2: The redirect should provide you with a `code`
 * parameter. You will now exchange this code for an access
 * and refresh token pair.
 */

$access_set = $OAuth->get("J9kkk2JbX7Yjl4L28fM13il46QI=", "http://requestb.in/19n5szz1");
print_r($access_set);


/**
 * Step 3: Exchange your expiring refresh token for another
 * access/refresh token pair.
 */

print_r($OAuth->refresh($access_set['refresh_token']));
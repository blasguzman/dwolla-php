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
require '../vendor/autoload.php';
$OAuth = new Dwolla\OAuth();

$OAuth->settings->sandbox = false;
$OAuth->settings->debug = true;

$OAuth->settings->client_id = "zbDwIC0dWCVU7cQtfvGwVwVjvxwQfjaTgkVi+FZOmKqPBzK5JG";
$OAuth->settings->client_secret = "ckmgwJz9h/fZ09unyXxpupCyrmAMe0bnUiMHF/0+SDaR9RHe99";

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

print($OAuth->genAuthUrl("http://requestb.in/yxlywryx"));

/**
 * Step 2: The redirect should provide you with a `code`
 * parameter. You will now exchange this code for an access
 * and refresh token pair.
 */

$access_set = $OAuth->get("Z/KHDIyWO/LboIGn3wGGs1+sRWg=", "http://requestb.in/yxlywryx");
print($access_set);


/**
 * Step 3: Exchange your expiring refresh token for another
 * access/refresh token pair.
 */

$access_set = $OAuth->refresh($access_set['refresh_token']); 
print_r($access_set);

/**
 * Step 4: Retrieve the catalog of endpoints that 
 * are available to the OAuth token which you just 
 * retrieved.
 */

print_r($OAuth->catalog($access_set['access_token']));
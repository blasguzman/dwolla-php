<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * The following is a quick-start example for the Checkouts class,
 * which encapsulates methods for all offsite-gateway related endpoints.
 */

// We need the Checkouts class in order to do anything
require '../lib/checkouts.php';
$Checkouts = new Dwolla\Checkouts();

/**
 * Optional:
 *
 * DwollaPHP has a very rudimentary shopping-cart
 * function. You can "make" your own cart and the
 * library will automatically calculate totals for you.
 *
 * For the purpose of this example, we will add two
 * cups of coffee to our cart.
 */

$Checkouts->addToCart("Coffee 1", "Mmm, fresh!", 2.25, 1);
$Checkouts->addToCart("Coffee 2", "Another coffee!", 2.25, 1);

/**
 * Step 1:
 *
 * Create a checkout session with the cart you have
 * just created. The array passed in contains members of the
 * `purchaseOrder` parameter. The second array marks this as a
 * `test` in order to...well...test.
 */

$test = $Checkouts->create(
    [ 'destinationId' => '812-111-7219' ],
    [ 'redirect' => 'http://requestb.in/1fglpx81' ]
);
print_r($test);

/**
 * Step 2:
 *
 * Verify the status of the recently created checkout.
 */

print_r($Checkouts->get($test['CheckoutId']));

/**
 * Step 3:
 *
 * Complete the checkout.
 */

print_r($Checkouts->complete('YOUR ORDER ID HERE'));

/**
 * Step 4:
 *
 * Verify gateway signature
 */

print_r($Checkouts->verify('YOUR SIGNATURE HERE', 'YOUR CHECKOUT ID HERE', 4.50));



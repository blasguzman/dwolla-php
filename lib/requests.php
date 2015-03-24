<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * This class contains methods for all exposed request related endpoints.
 *
 * create(): Request money from user.
 * get(): Lists all pending money requests.
 * info(): Retrieves info for a pending money request.
 * cancel(): Cancels a money request.
 * fulfill(): Fulfills a money request.
 */

namespace Dwolla;

require_once('client.php');

class Requests extends RestClient {

    /**
     * Requests money from a user for a user associated with
     * the current OAuth token.
     *
     * @param string $sourceId Dwolla ID to request funds from.
     * @param double $amount Amount to request.
     * @param string[] $params Additional parameters.
     *
     * @return int Request ID of submitted request.
     */
    public function create($sourceId, $amount, $params = false) {
        if (!$sourceId) { return self::_error("create() requires `\$sourceId` parameter.\n"); }
        if (!$amount) { return self::_error("create() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' => self::$settings->oauth_token,
            'sourceId' => $sourceId,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/requests/', $p);
    }

    /**
     * Retrieves a list of pending money requests for the user
     * associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     *
     * @return string[] Pending money requests and relevant data.
     */
    public function get($params = false) {
        $p = [
            'oauth_token' => self::$settings->oauth_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/requests', $p);
    }

    /**
     * Retrieves additional information about a pending money
     * request.
     *
     * @param string $request_id Request ID to retrieve info for.
     *
     * @return string[] Information relevant to the request.
     */
    public function info($request_id) {
        if (!$request_id) { return self::_error("info() requires `\$request_id` parameter.\n"); }

        return self::_get('/requests/' . $request_id,
            [
                'oauth_token' => self::$settings->oauth_token
            ]);
    }

    /**
     * Cancels a pending money request.
     *
     * @param string $request_id Request ID to cancel.
     *
     * @return null
     */
    public function cancel($request_id) {
        if (!$request_id) { return self::_error("cancel() requires `\$request_id` parameter.\n"); }

        return self::_post('/requests/' . $request_id . '/cancel',
            [
                'oauth_token' => self::$settings->oauth_token
            ]);
    }

    /**
     * Fulfills a pending money request.
     *
     * @param string $request_id Request ID to fulfill.
     * @param double $amount Amount to fulfill.
     * @param string[] $params Additional parameters.
     *
     * @return string[] Information (transaction/request IDs) relevant to fulfilled request.
     */
    public function fulfill($request_id, $amount, $params = false) {
        if (!$request_id) { return self::_error("fulfill() requires `\$request_id` parameter.\n"); }
        if (!$amount) { return self::_error("fulfill() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' => self::$settings->oauth_token,
            'pin' => self::$settings->pin,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/requests/' . $request_id . '/fulfill', $p);
    }
}
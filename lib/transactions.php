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
 * send(): Sends money
 * refund(): Refunds money
 * get(): Lists transactions for user
 * info(): Get information for transaction by ID.
 * stats(): Get transaction statistics for current user.
 */

namespace Dwolla;

require_once('client.php');

class Transactions extends RestClient {

    /**
     * Sends money to the specified destination user.
     *
     * @param string $destinationId Dwolla ID to send funds to.
     * @param double $amount Amount to send.
     * @param string[] $params Additional parameters.
     *
     * @return int Transaction ID of sent funds.
     */
    public function send($destinationId, $amount, $params = false) {
        if (!$destinationId) { return self::_error("send() requires `\$destinationId` parameter.\n"); }
        if (!$amount) { return self::_error("send() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' =>self::$settings->oauth_token,
            'pin' => self::$settings->pin,
            'destinationId' => $destinationId,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/transactions/send', $p);
    }

    /**
     * Lists transactions for the user associated with
     * the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     *
     * @return string[] List of transactions,
     */
    public function get($params = false) {
        $p = [
            'oauth_token' => self::$settings->oauth_token,
            'client_id' => self::$settings->client_id,
            'client_secret' => self::$settings->client_secret
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/transactions', $p);
    }

    /**
     * Returns transaction information for the transaction
     * associated with the passed transaction ID.
     *
     * @param string $id Transaction ID.
     *
     * @return string[] Information about transaction.
     */
    public function info($id) {
        if (!$id) { return self::_error("info() requires `\$id` parameter.\n"); }

        return self::_get('/transactions/' . $id,
            [
                'oauth_token' => self::$settings->oauth_token,
                'client_id' => self::$settings->client_id,
                'client_secret' => self::$settings->client_secret
            ]);
    }

    /**
     * Refunds (either completely or partially) funds to
     * the sending user for a transaction.
     *
     * @param string $id Transaction ID.
     * @param string $fundingSource Funding source for refund transaction.
     * @param double $amount Amount to refund.
     * @param string[] $params Additional parameters.
     *
     * @return string[] Information about refund transaction.
     */
    public function refund($id, $fundingSource, $amount, $params = false) {
        if (!$id) { return self::_error("refund() requires `\$id` parameter.\n"); }
        if (!$fundingSource) { return self::_error("refund() requires `\$fundingSource` parameter.\n"); }
        if (!$amount) { return self::_error("refund() requires `\$amount` parameter.\n"); }

        $p = [
            'oauth_token' => self::$settings->oauth_token,
            'pin' => self::$settings->pin,
	        'fundsSource' => $fundingSource,
            'transactionId' => $id,
            'amount' => $amount
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_post('/transactions/refund', $p);
    }

    /**
     * Retrieves transaction statistics for
     * the user associated with the current OAuth token.
     *
     * @param string[] $params Additional parameters.
     * @return string[] Transaction statistics.
     */
    public function stats($params = false) {
        $p = [
            'oauth_token' => self::$settings->oauth_token
        ];

        if ($params && is_array($params)) { $p = array_merge($p, $params); }

        return self::_get('/transactions/stats', $p);
    }
}

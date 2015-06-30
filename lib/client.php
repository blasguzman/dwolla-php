<?php

/**
 *      _               _ _
 *   __| |_      _____ | | | __ _
 *  / _` \ \ /\ / / _ \| | |/ _` |
 * | (_| |\ V  V / (_) | | | (_| |
 *  \__,_| \_/\_/ \___/|_|_|\__,_|

 * An official Guzzle based wrapper for the Dwolla API.

 * Support is available on our forums at: https://discuss.dwolla.com/category/api-support

 * @package Dwolla
 * @author Dwolla (David Stancu): api@dwolla.com, david@dwolla.com
 * @copyright Copyright (C) 2014 Dwolla Inc.
 * @license  MIT (http://opensource.org/licenses/MIT)
 * @version 2.1.6
 * @link http://developers.dwolla.com
 */

namespace Dwolla;

require_once '_settings.php';

class RestClient {

    /**
     * @var $settings
     *
     * Settings object.
     */
    public static $settings;

    /**
     * @var $client
     *
     * Placeholder for Guzzle REST client.
     */
    public static $client;

    /**
     * PHP "magic" getter.
     *
     * @param $name
     * @return $value
     */
    public function __get($name) {
        return $this->$name;
    }

   /**
     * PHP "magic" setter.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * Logs console messages to file for convenience.
     * (Thank you, @redzarf for your contribution)
     *
     * @param $data {???} Can be anything.
     */
    protected function _logtofile($data) {
        if (!empty(self::$settings->logfilePath) && file_exists(self::$settings->logfilePath . "/")) {
            file_put_contents(
                self::$settings->logfilePath . "/" . date("Y-m-d") . ".log",
                date("Y-m-d H:i:s") . '  ' . (is_array($data) ? print_r($data) : trim($data)) . "\n",
                FILE_APPEND
            );
        }
    }

    /**
     * Echos output and logs to console (and js console to make browser debugging easier).
     *
     * @param $data {???} Can be anything.
     */
    protected function _console($data)
    {
        if (self::$settings->debug) {
            if (self::$settings->browserMessages) {
                print("<script>console.log(");
                is_array($data) ? print_r($data) : print($data);
                print(");</script>\n\n");
                is_array($data) ? (print_r($data) && print("\n")) : print($data . "\n");
            }
            if (!empty(self::$settings->logfilePath)) {
                $this->_logtofile($data);
            }
        }
    }

    /**
     * Small error message wrapper for missing parameters, etc.
     *
     * @param string $message Error message.
     * @return bool
     */
    protected function _error($message) {
        print("DwollaPHP: " . $message);
        $this->_console("DwollaPHP: " . $message);
        return false;
    }

    /**
     * Parses API response out of envelope and informs user of issues if they arise.
     *
     * @param String[] $response Response body
     *
     * @return String[] Data from API
     */
    private function _dwollaparse($response)
    {
        if ($response['Success'] != true)
        {
            $this->_console("DwollaPHP: An API error was encountered.\nServer Message:\n");
            $this->_console($response['Message']);
            if ($response['Response']) {
                $this->_console("Server Response:\n");
                $this->_console($response['Response']);
            }
            return array('Error' => $response['Message']);
        }
        else {
            return $response['Response'];
        }
    }


    /**
     * Returns default host URL dependent on sandbox flag.
     *
     * @return string Host
     */
    protected function _host() {
        return self::$settings->sandbox ? self::$settings->sandbox_host : self::$settings->production_host;
    }

    /**
     * Wrapper around cURL POST request.
     *
     * @param string $endpoint API endpoint string
     * @param string $request Request body. JSON encoding is optional.
     * @param bool $customPostfix Use default REST postfix?
     * @param bool $dwollaParse Parse out of message envelope?
     *
     * @return String[] Response body.
     */
    protected function _post($endpoint, $request, $customPostfix = false, $dwollaParse = true) {

        $response = $this->curl($this->_host() . ($customPostfix ? $customPostfix : self::$settings->default_postfix) . $endpoint, 'POST', $request);

        if ($response) {
            // If we get a response, we parse it out of the Dwolla envelope and catch API errors.
            return $dwollaParse ? $this->_dwollaparse($response) : $response;
        }
        else {
            if (self::$settings->debug) {
                $this->_console("DwollaPHP: An error has occurred; the response body is empty");
            }
            return null;
        }
    }

    /**
     * Wrapper around cURL PUT request.
     *
     * @param string $endpoint API endpoint string
     * @param string $request Request body. JSON encoding is optional.
     * @param bool $customPostfix Use default REST postfix?
     * @param bool $dwollaParse Parse out of message envelope?
     *
     * @return String[] Response body.
     */
    protected function _put($endpoint, $request, $customPostfix = false, $dwollaParse = true) {

        $response = $this->curl($this->_host() . ($customPostfix ? $customPostfix : self::$settings->default_postfix) . $endpoint, 'PUT', $request);

        if ($response) {
            // If we get a response, we parse it out of the Dwolla envelope and catch API errors.
            return $dwollaParse ? $this->_dwollaparse($response) : $response;
        }
        else {
            if (self::$settings->debug) {
                $this->_console("DwollaPHP: An error has occurred; the response body is empty");
            }
            return null;
        }
    }    

    /**
     * Wrapper around cURL GET request.
     *
     * @param string $endpoint API endpoint string
     * @param string[] $query Array of URLEncoded query items in key-value pairs.
     * @param bool $customPostfix Use default REST postfix?
     * @param bool $dwollaParse Parse out of message envelope?
     *
     * @return string[] Response body.
     */
    protected function _get($endpoint, $query, $customPostfix = false, $dwollaParse = true) {

        $response = $this->curl($this->_host() . ($customPostfix ? $customPostfix : self::$settings->default_postfix) . $endpoint . '?' . http_build_query($query), 'GET');

        if ($response) {
            if ($response->getBody()) {
                // If we get a response, we parse it out of the Dwolla envelope and catch API errors.
                return $dwollaParse ? $this->_dwollaparse($response) : $response;
            }
        }
        else {
            if (self::$settings->debug) {
                $this->_console("DwollaPHP: An error has occurred; the response body is empty");
            }
            return null;
        }
    }

    /**
     * Wrapper around Guzzle DELETE request.
     *
     * @param string $endpoint API endpoint string
     * @param string[] $query Array of URLEncoded query items in key-value pairs.
     * @param bool $customPostfix Use default REST postfix?
     * @param bool $dwollaParse Parse out of message envelope?
     *
     * @return string[] Response body.
     */
    protected function _delete($endpoint, $query, $customPostfix = false, $dwollaParse = true) {

        $response = $this->curl($this->_host() . ($customPostfix ? $customPostfix : self::$settings->default_postfix) . $endpoint . '?' . http_build_query($query), 'DELETE');

        if ($response) {
            if ($response->getBody()) {
                // If we get a response, we parse it out of the Dwolla envelope and catch API errors.
                return $dwollaParse ? $this->_dwollaparse($response) : $response;
            }
        }
        else {
            if (self::$settings->debug) {
                $this->_console("DwollaPHP: An error has occurred; the response body is empty");
            }
            return null;
        }
    }

    /**
     * Use cURL to create HTTP requests. Parameters for 'GET' 
     * and 'DELETE' requests must be urlencoded and appended
     * to the URL in order to be fired correctly. 
     *
     * @param string $url URL string with endpoint
     * @param string $method HTTP verb used for request
     * @param array $params Parameters for request
     *
     * @return String[] Response body
     */
    protected function curl($url, $method, $params = false)
    {
        $valid_methods = array('GET', 'DELETE', 'PUT', 'POST');

        if (!in_array($method, $valid_methods)) {
            return $this->_console("DwollaPHP: Invalid HTTP verb.");
        }

        if (self::$settings->debug){
            $this->_console("$method Request to $url\n");
            if ($params) {
                $this->_console("    " . json_encode($params));
            }
        }

        // Set up cURL
        $curl_req = curl_init();        
        curl_setopt($curl_req, CURLOPT_URL, $url);
        curl_setopt($curl_req, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl_req, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl_req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_req, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl_req, CURLOPT_HEADER, false);

        $headers = array('Accept: application/json', 'Content-Type: application/json');

        // Configure appropriately for HTTP verb
        switch ($method) {
            case 'GET':
                curl_setopt($curl_req, CURLOPT_CUSTOMREQUEST, $method);
            case 'DELETE':
                curl_setopt($curl_req, CURLOPT_CUSTOMREQUEST, $method);
            case 'POST':
                $data = json_encode($params);
                $headers[] = 'Content-Length: ' . strlen($data);
                curl_setopt($curl_req, CURLOPT_POSTFIELDS, $data);
            case 'PUT':
                $data = json_encode($params);
                $headers[] = 'Content-Length: ' . strlen($data);
                curl_setopt($curl_req, CURLOPT_POSTFIELDS, $data);
        }

        // Set headers
        curl_setopt($curl_req, CURLOPT_HTTPHEADER, $headers);

        // cacert workaround
        if (strtoupper(substr(PHP_OS, 0,3)) == 'WIN') {
          $ca = dirname(__FILE__);
          curl_setopt($curl_req, CURLOPT_CAINFO, $ca); 
          curl_setopt($curl_req, CURLOPT_CAINFO, $ca . '/cacert.pem'); 
        }

        // Fire request, check for OK, close socket.
        $response = curl_exec($curl_req);

        if (curl_getinfo($curl_req, CURLINFO_HTTP_CODE) !== 200) {
            if (self::$settings->debug) {
                echo "DwollaPHP: We didn't get a 200 OK, here is what cURL says: \n";
                print_r(curl_getinfo($curl_req));
                print_r(curl_error($curl_req));
            }
            else { 
                return array(
                    'Success' => false,
                    'Message' => "Request failed. Server responded with: {$code}"
                );
            }
        }

        curl_close($curl_req);
        return json_decode($response, true);
    }

    /**
     * Constructor. Takes no arguments.
     */
    public function __construct() {

        self::$settings = new Settings();
        self::$settings->host = self::$settings->sandbox ?  self::$settings->sandbox_host : self::$settings->production_host;

        $this->settings = self::$settings;
    }
}


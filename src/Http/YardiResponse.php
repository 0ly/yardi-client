<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 9:52 AM
 */

namespace YardiClient\Http;

use SoapClient;

class YardiResponse
{
    private $client;

    public $resultStatus;
    public $resultMessage;

    public function __construct($response = null, $client = null)
    {
        if (isset($response)) {
            $this->parse($response);
        }

        if (isset($client) && $client instanceof SoapClient) {
            $this->client = $client;
        }
    }

    public function setSoapClient($client)
    {
        if ($client instanceof SoapClient) {
            $this->client = $client;
        }
    }

    public function getSoapClient()
    {
        return $this->client;
    }

    public function parse($response)
    {
        if (is_array($response)) {
            $result = array_shift($response);
            $xml = $result->any;
            $matches = array();
            if (preg_match('/<ResultStatus>(?<status>[^\>]*)<\/ResultStatus>/', $xml, $matches) != false) {
                $this->resultStatus = $matches['status'];
            }
            if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $xml, $matches) != false) {
                $this->resultMessage = $matches['message'];
            }
        }
        elseif (is_string($response)) {
            $matches = array();
            if (preg_match('/<ResultStatus>(?<status>[^\>]*)<\/ResultStatus>/', $response, $matches) != false) {
                $this->resultStatus = $matches['status'];
            }
            if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $response, $matches) != false) {
                $this->resultMessage = $matches['message'];
            }
        }
        elseif (is_soap_fault($response)) {
            $this->resultStatus = $response->getCode();
            $this->resultMessage = $response->getMessage();
        }
    }
}

<?php

namespace YardiClient\Http;

class YardiPingResponse extends YardiResponse
{
    public function parse($response)
    {
        if (is_object($response)) {
            var_dump($response);
            $xml = $response->PingResult->any;
            if (preg_match('/<ResultStatus>(?<status>[^>]*)<\/ResultStatus>/', $xml, $matches) !== false) {
                $this->resultStatus = $matches['status'];
            }
            if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $xml, $matches) !== false) {
                $this->resultMessage = $matches['message'];
            }
        }
        else {
            parent::parse($response);
        }
    }
}

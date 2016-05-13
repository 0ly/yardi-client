<?php

namespace YardiClient\Http;

use YardiClient\Http\YardiResponse;

class YardiImportGuestResponse extends YardiResponse
{
    public function parse($response)
    {
        if (is_object($response)) {
            $xml = $response->ImportGuestResult->any;
            if (preg_match('/<ResultStatus>(?<status>[^>]*)<\/ResultStatus>/', $xml, $matches) !== false) {
                $this->resultStatus = $matches['status'];
            }
            if (preg_match('/<ResultMessage>(?<message>[^>]*)<\/ResultMessage>/', $xml, $matches) !== false) {
                $this->resultMessage = $matches['message'];
            }
        }
        else {
            parent::parse($response);
        }
    }
}

<?php

namespace YardiClient\Http;

use YardiClient\Http\YardiRequest;

class YardiPingRequest extends YardiRequest
{
    public function __construct($url = null, $user = null, $pass = null, $db = null, $servName = null, $plat = YardiPlatform::SQL_SERVER)
    {
        parent::__construct($url, 'Ping', $user, $pass, $db, $servName, $plat);
    }

    public function send()
    {
        $response = parent::send();
        var_dump($response->getSoapClient()->__getLastResponse());
        return $response instanceof YardiResponse ?
            new YardiPingResponse($response->getSoapClient()->__getLastResponse(), $response->getSoapClient())
            : new YardiPingResponse(parent::send());
    }
}

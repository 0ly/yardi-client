<?php

namespace YardiClient\Http;

use Yardi\Http\Request;

class YardiImportGuestRequest extends YardiRequest
{
    protected $xmlDoc;

    public function __construct($url = null, $user = null, $pass = null, $db = null, $servName = null, $plat = YardiPlatform::SQL_SERVER, YardiXmlDoc $xml = null)
    {
        parent::__construct($url, 'ImportGuest', $user, $pass, $db, $servName, $plat);

        $this->xmlDoc = $xml;
    }

    public function setXmlDoc(YardiXmlDoc $xml)
    {
        $this->xmlDoc = $xml;
    }

    public function getXmlDoc()
    {
        return $this->xmlDoc;
    }

    public function build()
    {
        $params = parent::build();

        if (isset($this->xmlDoc)) {
            $params['Xmldoc'] = new SoapVar(
                trim(preg_replace('/^.+(\n|\r|\r\n)/', '', (string)$this->getXmlDoc())),
                XSD_ANYXML
            );
        }

        return $params;
    }

    public function send()
    {
        $response = parent::send();
        return $response instanceof YardiResponse ?
            new YardiImportGuestResponse($response->getSoapClient()->__getLastResponse(), $response->getSoapClient())
            : new YardiImportGuestResponse(parent::send());
    }
}

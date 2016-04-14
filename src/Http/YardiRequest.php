<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 9:39 AM
 */

namespace YardiClient\Http;

use SoapVar;
use SoapFault;
use SoapParam;
use SoapClient;
use YardiClient\Config\YardiPlatform;
use YardiClient\Http\YardiResponse;

class YardiRequest {
    public $url;
    protected $function;
    protected $username;
    protected $password;
    protected $database;
    protected $server;
    protected $platform;

    public function __construct($url = null, $function = null, $user = null, $pass = null, $db = null, $servName = null, $plat = YardiPlatform::SQL_SERVER)
    {
        $this->url = $url;
        $this->function = $function;
        $this->username = $user;
        $this->password = $pass;
        $this->database = $db;
        $this->server = $servName;
        $this->platform = $plat;
    }

    public function setURL($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getURL()
    {
        return $this->url;
    }

    public function setFunction($func)
    {
        $this->function = $func;
        return $this;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setUsername($user)
    {
        $this->username = $user;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($pass)
    {
        $this->password = $pass;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function setDatabase($db)
    {
        $this->database = $db;
        return $this;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
        return $this;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function build()
    {
        $params = array();
        $params['DbUserName'] = new SoapVar($this->getUsername(), XSD_STRING);
        $params['DbPassword'] = new SoapVar($this->getPassword(), XSD_STRING);
        $params['DbName'] = new SoapVar($this->getDatabase(), XSD_STRING);
        $params['Server'] = new SoapVar($this->getServer(), XSD_STRING);
        $params['Platform'] = new SoapVar($this->getPlatform(), XSD_STRING);
        return $params;
    }

    public function send() {
        $client = new SoapClient($this->getURL(), array('trace' => 1));
        $client->__setLocation($this->getURL());

        $params = new SoapParam($this->build(), $this->getFunction());

        try {
            $response = $client->{$this->getFunction()}($params);
            return !is_object($response) || !($response instanceof YardiResponse)
                ? new YardiResponse($response, $client) : $response;
        }
        catch (SoapFault $exception) {
            return new YardiResponse($exception, $client);
        }
    }
}

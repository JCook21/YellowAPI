<?php

namespace YellowAPI;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Class: YellowAPIClient
 *
 * @package YellowAPI
 * @author  Jeremy Cook <jeremycook0@gmail.com>
 *
 * @see     Guzzle\Service\Client
 */
class YellowAPIClient extends Client
{
    /**
     * Production url of the API
     */
    const PROD_URL = 'http://api.yellowapi.com/';

    /**
     * Production url of the API
     */
    const SANDBOX_URL = 'http://api.sandbox.yellowapi.com/';

    /**
     * Factory method to configure the client
     * @param array $config
     *
     * @return YellowAPI\YellowAPIClient
     */
    public static function factory($config = [])
    {
        //Default values to use
        $default = array(
            'base_url' => self::PROD_URL,
        );

        //Required parameters when creating a client.
        $required = array(
            'base_url',
        );

        $config = Collection::fromConfig($config, $default, $required);
        $client = new self($config->get('base_url'), $config);
        $client->setDescription(ServiceDescription::factory(__DIR__ . '/Resources/yellowapi.json'));

        return $client;
    }
}

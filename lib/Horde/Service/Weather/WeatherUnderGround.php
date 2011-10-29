<?php
/**
 * This file contains the Horde_Service_Weather class for communicating with
 * the weather underground service.
 *
 * Copyright 2011 Horde LLC (http://www.horde.org/)
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @license  http://www.horde.org/licenses/bsd BSD
 * @category Horde
 * @package  Service_Weather
 */

/**
 * Horde_Service_Weather_WeatherUnderground.
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @category Horde
 * @package  Service_Weather
 */
class Horde_Service_Weather_WeatherUnderground extends Horde_Service_Weather_Base
 {

    const API_URL = 'http://api.wunderground.com';

    /**
     * The http client
     *
     * @var Horde_Http_Client
     */
    protected $_http;

    /**
     * Local cache of current conditions
     *
     */
    protected $_current;

    /**
     * Local cache of forecast
     *
     * @var array
     */
    protected $_forecast = array();

    /**
     * Constructor
     *
     * @param Horde_Service_Weather_Location_Base $location  The location object.
     * @param array $params                                  Parameters.
     *
     * @return Horde_Service_Weather_Base
     */
    public function __construct(
        Horde_Service_Weather_Location_Base $location,
        array $params = array())
    {
        // Check required api key parameters here...
        if (empty($params['http_client']) || empty($params['apikey'])) {
            throw InvalidArgumentException('Missing required http_client parameter.');
        }
        $this->_http = $params['http_client'];
        unset($params['http_client']);
        $this->_apiKey = $params['apikey'];
        unset($params['apikey']);

        parent::__construct($location, $params);
    }

    /**
     * Obtain the current observations.
     *
     * @return Horde_Service_Weather_Current
     */
    public function getCurrentConditions()
    {
        $this->_getCommonElements();
    }

    /**
     * Obtain the forecast for the current location.
     *
     * @return Horde_Service_Weather_Forecast
     */
    public function getForecast($type)
    {
        $this->_getCommonElements();
    }

    /**
     * Weather Underground allows requesting multiple features per request,
     * and only counts it as a single request against your API key. So we trade
     * a bit of request time/traffic for a smaller number of requests to obtain
     * information for e.g., a typical weather portal display.
     */
    protected function _getCommonElements()
    {
        if (!empty($this->_current)) {
            return;
        }

        $url = $this->_addJsonFormat(
            $this->_addLocation(
                $this->_addForecastFeature(
                    $this->_addConditionFeature(
                        $this->_addGeoLookupFeature($this->_addApiKey(self::API_URL))
                    )
                )
            )
        );

        $results = $this->_makeRequest($url);

        // @TODO: parse results, break into forecast/current/location objects
    }

    protected function _makeRequest($url)
    {
        $url = new Horde_Url($url);

        return $this->_http->get($url);
    }

    protected function _addLocation($url)
    {
        return $url . '/q/' . $this->_location->getLocationCode();
    }

    protected function _addApiKey($url)
    {
        return $url . '/api/' . $this->_apiKey;
    }

    protected function _addGeoLookupFeature($url)
    {
        return $url . '/geolookup';
    }

    protected function _addConditionFeature($url)
    {
        return $url . '/conditions';
    }

    protected function _addForecastFeature($url)
    {
        return $url . '/forecast';
    }

    protected function _addJsonFormat($url)
    {
        return $url . '.json';
    }

 }
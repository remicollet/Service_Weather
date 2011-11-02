<?php
/**
 * This file contains the Horde_Service_Weather_Period class for abstracting
 * access to a single forecast period.
 *
 * Copyright 2011 Horde LLC (http://www.horde.org/)
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @license  http://www.horde.org/licenses/bsd BSD
 * @category Horde
 * @package  Service_Weather
 */

/**
 * Horde_Service_Weather_Period class
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @category Horde
 * @package  Service_Weather
 */
class Horde_Service_Weather_Period_Base
{

    protected $_map = array(

    );

    public function __get($property)
    {
        switch ($property) {
        case 'date':
            $date = new Horde_Date($this->_properties['date']);
            var_dump($date);
        }
    }

}
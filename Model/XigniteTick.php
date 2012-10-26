<?php
/**
 * Tick
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('XigniteFuturesModel', 'Xignite.Model');

class XigniteTick extends XigniteFuturesModel {

/**
 * Map of which query should be used for a given set of parameters
 * 
 * @var string
 */
    public $xignite_queries = array(
        'AsOfDate_EndTime_Month_StartTime_Symbol_TickPeriods_TickPrecision_Year' => array('query' => 'GetHistoricalTicksAsOfDate', 'path' => 'Ticks.Ticks.Tick.{n}'),
        'EndTime_Month_StartTime_Symbol_TickPeriods_TickPrecision_Year' => array('query' => 'GetTicks', 'path' => 'Ticks.Ticks.Tick.{n}'),
        'Month_Symbol_Time_Year' => array('query' => 'GetTick', 'path' => 'SingleTick')
    );

/**
 * Subscription schema
 *
 * @var array
 */
	public $_schema = array(
        'delay' => array('type' => 'float'),
        'time' => array('type' => 'datetime'),
        'price' => array('type' => 'float'),
        'quantity' => array('type' => 'integer'),
        'open' => array('type' => 'float'),
        'high' => array('type' => 'float'),
        'low' => array('type' => 'float'),
        'change' => array('type' => 'float'),
        'percentchange' => array('type' => 'float')
	);

}

<?php
/**
 * Future Quote
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('XigniteFuturesModel', 'Xignite.Model');

class XigniteFutureQuote extends XigniteFuturesModel {

/**
 * Map of which query should be used for a given set of parameters
 * 
 * @var string
 */
    public $xignite_queries = array(
        'EndDate_StartDate_Symbol' => array('query' => 'GetHistoricalCommodityRange', 'path' => 'FutureQuotes.Quotes.FutureQuote.{n}'),
        'Month_Symbol_Year' => array('query' => 'GetDelayedFuture', 'path' => 'FutureQuote'),
        'Symbol' => array('query' => 'GetDelayedSpot', 'path' => 'FutureQuote')
    );

/**
 * Subscription schema
 *
 * @var array
 */
	public $_schema = array(
        'delay' => array('type' => 'float'),
        'future' => array('type' => array(
            'delay' => array('type' => 'float'),
            'symbol' => array('type' => 'string'),
            'name' => array('type' => 'string'),
            'month' => array('type' => 'integer'),
            'year' => array('type' => 'integer'),
            'exchange' => array('type' => 'string'),
            'exchangesymbol' => array('type' => 'string'),
            'type' => array('type' => 'string')
        )),
        'date' => array('type' => 'date'),
        'open' => array('type' => 'float'),
        'high' => array('type' => 'float'),
        'low' => array('type' => 'float'),
        'last' => array('type' => 'float'),
        'settle' => array('type' => 'float'),
        'volume' => array('type' => 'integer'),
        'openinterest' => array('type' => 'integer'),
        'previousclose' => array('type' => 'float'),
        'change' => array('type' => 'float'),
        'percentchange' => array('type' => 'float'),
        'currency' => array('type' => 'string')
	);

}

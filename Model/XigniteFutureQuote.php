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
 * The identifier for the query
 * 
 * @var string
 */
    public $xignite_query = 'GetHistoricalCommodityRange';

/**
 * Subscription schema
 *
 * @var array
 */
	public $_schema = array(
        'Outcome' => array('type' => 'string'),
        'Delay' => array('type' => 'integer'),
        'Future' => array(
            'Outcome' => "Success",
            'Delay' => "0",
            'Symbol' => "ZC",
            'Name' => "Corn",
            'Month' => "12",
            'Year' => "2012",
            'Exchange' => "CBOT",
            'ExchangeSymbol' => "ZCZ2",
            'Type' => "Future"
        ),
        'Date' => array('type' => 'date'),
        'Open' => array('type' => 'float'),
        'High' => array('type' => 'float'),
        'Low' => array('type' => 'float'),
        'Last' => array('type' => 'float'),
        'Settle' => array('type' => 'float'),
        'Volume' => array('type' => 'integer'),
        'OpenInterest' => array('type' => 'integer'),
        'PreviousClose' => array('type' => 'float'),
        'Change' => array('type' => 'float'),
        'PercentChange' => array('type' => 'float'),
        'Currency' => array('type' => 'string')
	);

}

<?php
/**
 * Xignite Source Test
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2011, Jeremy Harris
 * @link http://42pixels.com
 * @package stripe
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('XigniteAppModel', 'Xignite.Model');
App::uses('HttpSocket', 'Network/Http');

class TestXigniteModel extends XigniteAppModel {
}

/**
 * Xignite Source Test
 *
 * @package stripe
 * @subpackage Xignite.Test.Model.Datasource
 */
class XigniteSourceTest extends CakeTestCase {

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		ConnectionManager::loadDatasource(array(
			'plugin' => 'Xignite',
			'classname' => 'XigniteSource'
		));

		$this->Source = new XigniteSource(array(
			'key' => '123456'
		));
		$this->Source->Http = $this->getMock('HttpSocket', array('request'));
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Source);
	}

/**
 * testConstructWithoutKey
 *
 * @return void
 */
	public function testConstructWithoutKey() {
		$this->setExpectedException('CakeException');
		$source = new XigniteSource();
	}

/**
 * mapQuery()
 * 
 * Condition: A normal set of valid unordered query options.
 * Result: The correct configuration for the query.
 */
    public function testMapQuery() {
        $model = new TestXigniteModel();
        $model->xignite_queries = array(
            'EndDate_StartDate_Symbol' => array('query' => 'GetHistoricalCommodityRange', 'path' => 'FutureQuotes.Quotes.FutureQuote.{n}'),
            'Month_Symbol_Year' => array('query' => 'GetDelayedFuture', 'path' => 'FutureQuote'),
            'Symbol' => array('query' => 'GetDelayedSpot', 'path' => 'FutureQuote')
        );
        $request = array('Symbol' => 'ZC', 'StartDate' => '10/01/2012', 'EndDate' => '10/30/2012');
        $result = $this->Source->mapQuery($model, $request);
        $this->assertEqual($result, $model->xignite_queries['EndDate_StartDate_Symbol']);
    }

/**
 * mapQuery()
 * 
 * Condition: Invalid query options.
 * Result: false
 */
    public function testMapQueryInvalid() {
        $model = new TestXigniteModel();
        $model->xignite_queries = array(
            'EndDate_StartDate_Symbol' => array('query' => 'GetHistoricalCommodityRange', 'path' => 'FutureQuotes.Quotes.FutureQuote.{n}'),
            'Month_Symbol_Year' => array('query' => 'GetDelayedFuture', 'path' => 'FutureQuote'),
            'Symbol' => array('query' => 'GetDelayedSpot', 'path' => 'FutureQuote')
        );
        $request = array('Symbol' => 'ZC', 'Year' => '2012');
        $result = $this->Source->mapQuery($model, $request);
        $this->assertFalse($result);
    }
    
/**
 * testRequest
 *
 * @return void
 */
	public function testRequest() {
	}

/**
 * testRead
 *
 * @return void
 */
	public function testRead() {
	}

}
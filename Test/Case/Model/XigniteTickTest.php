<?php
/* Activity Test cases generated on: 2012-06-04 23:18:13 : 1338866293*/
App::uses('XigniteTick', 'Xignite.Model');

/**
 * Activity Test Case
 *
 */
class XigniteTickTestCase extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Model = new XigniteTick();
		$this->Model->setDataSource('xignite_test');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);

		parent::tearDown();
	}

/**
 * read
 * 
 * Condition:
 */
    public function testRead() {
        $result = $this->Model->find('all', array(
            'conditions' => array(
                'Symbol' => 'ZC'
            )
        ));
        debug($result);
        $this->assertFalse(empty($result));

        $result = $this->Model->find('all', array(
            'conditions' => array(
                'Symbol' => 'ZC',
                'Month' => 12,
                'Year' => 2012,
                'Time' => '14:30'
            )
        ));
        debug($result);
        $this->assertFalse(empty($result));

        $result = $this->Model->find('all', array(
            'conditions' => array(
                'Symbol' => 'ZC',
                'Month' => 12,
                'Year' => 2012,
                'StartTime' => '12:30',
                'EndTime' => '14:30',
                'TickPeriods' => 1,
                'TickPrecision' => 'Minute'
            )
        ));
        debug($result);
        $this->assertFalse(empty($result));
    }

/**
 * read
 * 
 * Condition: The parameters are invalid.
 * Result: false 
 */
    public function testInvalidRead() {
        $result = $this->Model->find('all', array(
            'conditions' => array(
                'Symbol' => 'ZCADFS',
                'StartDate' => '9/1/2012',
                'EndDate' => '9/30/2012'
            )
        ));
        $this->assertFalse($result);
    }

}

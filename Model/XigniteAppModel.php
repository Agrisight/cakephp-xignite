<?php
/**
 * Xignite app model
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');

/**
 * XigniteAppModel
 *
 * @package xignite
*/
class XigniteAppModel extends AppModel {

/**
 * The datasource
 *
 * @var string
 */
	public $useDbConfig = 'xignite';

/**
 * No table here
 *
 * @var mixed
 */
	public $useTable = false;

/**
 * Which service (xFutures, xEnergy, etc)
 * 
 * @var string
 */
    public $xignite_service = null;

/**
 * The identifier for the query
 * 
 * @var string
 */
    public $xignite_queries = array();

/**
 * Returns the last error from Xignite
 *
 * @return string Error
 */
	public function getXigniteError() {
		$ds = ConnectionManager::getDataSource($this->useDbConfig);
		return $ds->lastError;
	}

}

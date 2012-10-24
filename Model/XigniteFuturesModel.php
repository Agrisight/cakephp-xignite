<?php
/**
 * Xignite Futures model
 *
 * Abstract model for any models designed to query the xFutures API.
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('XigniteAppModel', 'Xignite.Model');

/**
 * XigniteAppModel
 *
 * @package xignite
*/
class XigniteFuturesModel extends XigniteAppModel {

/**
 * Which service (xFutures, xEnergy, etc)
 * 
 * @var string
 */
    public $xignite_service = 'xFutures';

/**
 * The identifier for the query
 * 
 * @var string
 */
    public $xignite_query = null;

}

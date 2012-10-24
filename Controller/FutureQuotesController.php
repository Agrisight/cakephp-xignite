<?php
/**
 * Xignite app controller
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('XigniteAppController', 'Xignite.Controller');
App::uses('XigniteFutureQuote', 'Xignite.Model');

class FutureQuotesController extends XigniteAppController {
    
    public $uses = array('Xignite.XigniteFutureQuote');

    public function index() {
        $results = $this->XigniteFutureQuote->find('all', array(
            'conditions' => $this->request->query
        ));

        if (! $results) {
            throw new InternalErrorException('Could not retrieve results.');
        }

        echo json_encode(Set::extract($results, '{n}.XigniteFutureQuote'));
    }

}

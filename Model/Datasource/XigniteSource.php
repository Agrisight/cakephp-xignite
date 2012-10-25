<?php
/**
 * Xignite datasource
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @subpackage xignite.models.datasources
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');
App::uses('CakeLog', 'Log');

if (! function_exists('array_change_key_case_recursive')) {
    function array_change_key_case_recursive($array, $case = CASE_LOWER) {
        $new = array();

        foreach ($array as $k => $v) {
            $new[$k] = (is_array($v)) ? array_change_key_case_recursive($v, $case) : $v;
        }

        return array_change_key_case($new, $case);
    }
}

/**
 * StripSource
 *
 * @package xignite
 * @subpackage xignite.models.datasources
 */
class XigniteSource extends DataSource {

/**
 * HttpSocket
 *
 * @var HttpSocket
 */
	public $Http = null;

/**
 * Start quote
 * 
 * @var string 
 */
	public $startQuote = '';

/**
 * End quote
 * 
 * @var string 
 */
	public $endQuote = '';

/**
 * Constructor. Sets API key and throws an error if it's not defined in the
 * db config
 *
 * @param array $config
 */
	public function __construct($config = array()) {
		parent::__construct($config);

		if (empty($config['key'])) {
			throw new CakeException('XigniteSource: Missing api key');
		}

		$this->Http = new HttpSocket();
	}

/**
 * Reads a Xignite record
 *
 * @param Model $model The calling model
 * @param array $queryData Query data (conditions, limit, etc)
 * @return mixed `false` on failure, data on success
 */
	public function read($model, $queryData = array()) {
        $request = array();

		// If calculate() wants to know if the record exists. Say yes.
		if ($queryData['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}

        if (!empty($queryData['conditions'])) {
            $request['uri']['query'] = $queryData['conditions'];
        }

		$response = $this->request($model, $request);

		if ($response === false) {
			return false;
		}

        $result = array();
        foreach (Set::extract($response, 'FutureQuote.{n}') as $record) {
            $result[] = array($model->alias => array_change_key_case_recursive($record, CASE_LOWER));
        }

		return $result;
	}

/**
 * Submits a request to Xignite. Requests are merged with default values, such as
 * the api host. If an error occurs, it is stored in `$lastError` and `false` is
 * returned.
 *
 * @param array $request Request details
 * @return mixed `false` on failure, data on success
 */
	public function request($model, $request = array()) {
		$this->lastError = null;
		$this->request = array(
			'uri' => array(
				'host' => 'www.xignite.com',
				'scheme' => 'http',
				'path' => '/',
                'query' => array(
                    'Header_Username' => $this->config['key']
                )
			),
			'method' => 'GET',
		);
		$this->request = Set::merge($this->request, $request);
		$this->request['uri']['path'] = '/' . $model->xignite_service . '.asmx/' . $model->xignite_query;

		try {
			$http_response = $this->Http->request($this->request);

			if ($this->Http->response['status']['code'] != '200') {
                $this->lastError = 'Unexpected error.';
                CakeLog::write('xignite', $this->lastError);
                return false;
			}

            $response = Xml::toArray(Xml::build($http_response->body));

            if ($response['FutureQuotes']['Outcome'] !== 'Success') {
                $this->lastError = $response['FutureQuotes']['Message'];
            }

            return $response['FutureQuotes']['Quotes'];
		} catch (CakeException $e) {
			$this->lastError = $e->getMessage();
			CakeLog::write('xignite', $e->getMessage());
		}
	}

/**
 * For checking if record exists. Return COUNT to have read() say yes.
 *
 * @param Model $Model
 * @param string $func
 * @return true
 */
	public function calculate(Model $Model, $func) {
		return 'COUNT';
	}

/**
 * Don't use internal caching
 *
 * @return null
 */
	public function listSources() {
		return null;
	}

/**
 * Descibe with schema. Check the model or use nothing.
 *
 * @param Model $Model
 * @return array
 */
	public function describe(Model $Model) {
		if (isset($Model->_schema)) {
			return $Model->_schema;
		} else {
			return null;
		}
	}

}
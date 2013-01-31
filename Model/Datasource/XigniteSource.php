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
 * @param array $query Query data (conditions, limit, etc)
 * @return mixed `false` on failure, data on success
 */
	public function read(Model $model, $query = array(), $recursive = null) {
		// If calculate() wants to know if the record exists. Say yes.
		if ($query['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}

        if (! isset($query['conditions'])) {
            $query['conditions'] = array();
        }

		$response = $this->request($model, $query['conditions']);

		if ($response === false) {
			return false;
		}

        $result = array();
        foreach ($response as $record) {
            $result[] = array($model->alias => $record);
        }

		return $result;
	}

/**
 * Submits a request to Xignite. If an error occurs, it is stored in
 * `$lastError` and `false` is returned.
 *
 * @param array $conditions
 * @return mixed `false` on failure, data on success
 */
	public function request($model, $request = array()) {
        $query = $this->mapQuery($model, $request);

        if (! $query) {
            $this->lastError = 'Unrecognized set of query parameters.';
            return false;
        }

		$this->lastError = null;
		$this->request = array(
			'uri' => array(
				'host' => 'www.xignite.com',
				'scheme' => 'http',
				'path' => '/' . $model->xignite_service . '.asmx/' . $query['query'],
                'query' => array_merge($query['params'], array(
                    'Header_Username' => $this->config['key']
                ))
			),
			'method' => 'GET',
		);

		try {
			$http_response = $this->Http->request($this->request);

			if ($this->Http->response['status']['code'] != '200') {
                $this->lastError = 'Unexpected error.';
                CakeLog::write('xignite', $this->lastError);
                return false;
			}

            $document = Xml::toArray(Xml::build($http_response->body));

            $contents = current($document);
            if ($contents['Outcome'] !== 'Success') {
                $this->lastError = $contents['Message'];
                return false;
            }

            $data = Set::extract($document, $query['path']);
            
            if (empty($data)) {
                $this->lastError = 'No data found.';
                return false;
            }
            
            $data = isset($data[0]) ? $data : array($data);
            $data = array_change_key_case_recursive($data, CASE_LOWER);
            
            return $this->clean($model, $data);
		} catch (CakeException $e) {
			$this->lastError = $e->getMessage();
			CakeLog::write('xignite', $e->getMessage());
		}
	}
    
/**
 * Given a set of request parameters, determine which query should be used.
 * 
 * @param type $model   The model being requested
 * @param type $request The parameters given
 * @return mixed
 */
    public function mapQuery($model, $request) {
        $params = array_keys($request);
        sort($params);
        $key = implode('_', $params);

        if (! isset($model->xignite_queries[$key])) {
            return false;
        }
        
        $query = $model->xignite_queries[$key];
        
        if (isset($query['defaults']) && is_array($query['defaults'])) {
            $query['params'] = array_merge($query['defaults'], $request);
        } else {
            $query['params'] = $request;
        }

        return $query;
    }

/**
 * Clean up the response to match the known schema.
 * 
 * @param type $model
 * @param type $data
 * @return type 
 */
    public function clean($model, $data) {
        $schema = $this->describe($model);
        
        if (empty($schema)) {
            return $data;
        }

        foreach ($data as $k => $v) {
            $data[$k] = array_intersect_key(array_merge(array_fill_keys(array_keys($schema), null), $v), $schema);
        }

        return $data;
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
	public function listSources($data = null) {
		return null;
	}

/**
 * Descibe with schema. Check the model or use nothing.
 *
 * @param Model $Model
 * @return array
 */
	public function describe($model) {
		if (isset($Model->_schema)) {
			return $Model->_schema;
		} else {
			return null;
		}
	}

}

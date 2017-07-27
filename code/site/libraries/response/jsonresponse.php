<?php
/**
 * @package com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://techjoomla.com
 * Work derived from the original RESTful API by Techjoomla (https://github.com/techjoomla/Joomla-REST-API) 
 * and the com_api extension by Brian Edgerton (http://www.edgewebworks.com)
*/

class APIJSONResponse
{
	var $err_msg = '';

	var $err_code = '';

	var $response_id = '';

	var $api = '';

	var $version = '';

	var $data = null;

	protected $callbackname = 'callback';

	public function __construct($response)
	{
		$app = JFactory::getApplication();
		$this->data = new \stdClass;

		if ($response instanceof Exception) {
			$this->err_msg = $response->getMessage();
			$this->err_code = $response->getCode();
		}
		else
		{
			$this->api = "{$response->component}.{$response->resource}";
			$this->response_id = $response->response_id;			
			$this->data->results = $response->get('response');
		}
	}

	/**
	 * Transforms the plugin response to a JSON-encoded string
	 * Can also return JSONP if the callback is set
	 *
	 * @return  string
	 *
	 * @since 1.0
	 */
	public function __toString()
	{
		$app = JFactory::getApplication();
		$callback = $app->input->get($this->callbackname, '', 'CMD');
		echo $compatibility = $app->input->server->get('HTTP_X_COMPATIBILITY_MODE', 0, 'INT');

		$response = $compatibility ? $this->data->results : $this;

		if ($callback)
		{
			return $callback . '(' . json_encode($response) . ')';
		}
		else
		{
			return json_encode($response);
		}
	}
}

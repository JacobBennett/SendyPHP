<?php 
/**
* Sendy Class
*/
class SendyLibrary
{
	private $installation_url = 'http://updates.mydomain.com';
	private $api_key = 'yourapiKEYHERE';
	private $list_id;
	
	function __construct($list_id)
	{
		//error checking
		if (!isset($list_id)) {throw new Exception("Required config parameter [list_id] is not set", 1);}
		if (!isset($this->installation_url)) {throw new Exception("Required config parameter [installation_url] is not set", 1);}
		if (!isset($this->api_key)) {throw new Exception("Required config parameter [api_key] is not set", 1);}

		$this->list_id = $list_id;

	}

	public function subscribe(array $values) {
		$type = 'subscribe';

		//Send the subscribe
		$result = $this->buildAndSend($type, $values);

		//Handle results
		switch ($result) {
			case 'true':
					return array(
					'status' => true,
					'message' => 'Subscribed'
					);
				break;

			case 'Already subscribed.':
					return array(
					'status' => true,
					'message' => 'Already subscribed.'
					);
				break;
			
			default:
					return array(
					'status' => false,
					'message' => $result
					);
				break;
		}



	}

	public function unsubscribe($email) {
		$type = 'unsubscribe';
		
		//Send the unsubscribe
		$result = $this->buildAndSend($type, array('email' => $email));

		//Handle results
		switch ($result) {
			case 'true':
					return array(
					'status' => true,
					'message' => 'Unsubscribed'
					);
				break;
			
			default:
					return array(
					'status' => false,
					'message' => $result
					);
				break;
		}

	}

	public function substatus($email) {
		$type = 'api/subscribers/subscription-status.php';
		
		//Send the request for status
		$result = $this->buildAndSend($type, array('email' => $email, 'api_key' => $this->api_key, 'list_id' => $this->list_id));

		//Handle the results
		switch ($result) {
			case 'Subscribed':
			case 'Unsubscribed':
			case 'Unconfirmed':
			case 'Bounced':
			case 'Soft bounced':
			case 'Complained':
					return array(
					'status' => true,
					'message' => $result
					);
				break;
			
			default:
					return array(
					'status' => false,
					'message' => $result
					);
				break;
		}

	}

	public function subcount($list = "") {
		$type = 'api/subscribers/active-subscriber-count.php';

		//handle exceptions
		if($list== "" && $this->list_id == "") {throw new Exception("method [subcount] requires parameter [list] or [$this->list_id] to be set.", 1);}

		//if a list is passed in use it, otherwise use $this->list_id
		if($list == "") {$list = $this->list_id;}

		//Send request for subcount
		$result = $this->buildAndSend($type, array('api_key' => $this->api_key, 'list_id' => $list));
		
		//Handle the results
		if (is_int($result)) {
			return array(
					'status' => true,
					'message' => $result
					);
		}

		//Error
		return array(
		'status' => false,
		'message' => $result
		);

	}

	private function buildAndSend($type, array $values) {

		//error checking
		if (!isset($type)) {throw new Exception("Required config parameter [type] is not set", 1);}
		if (!isset($values)) {throw new Exception("Required config parameter [values] is not set", 1);}

		//Global options for return
		$return_options = array(
			'list' => $this->list_id,
			'boolean' => 'true'
			);

		//Merge the passed in values with the options for return
		$content = array_merge($values, $return_options);

		//build a query using the $content
		$postdata = http_build_query($content);

		$opts = array('http' => array('method'  => 'POST', 'header'  => 'Content-type: application/x-www-form-urlencoded', 'content' => $postdata));
		$context  = stream_context_create($opts);
		$result = file_get_contents($this->installation_url .'/'. $type, false, $context);

		return $result;
	}

	/**
	*	Magic methods below
	*	http://www.php.net/manual/en/language.oop5.overloading.php
	*/
	public function __get($property) {

	    if (property_exists($this, $property)) {
      		return $this->$property;
	    }

	}

  	public function __set($property, $value) {

		    if (property_exists($this, $property)) {
	      		$this->$property = $value;
		    }

		    return $this;
  	}

}

?>

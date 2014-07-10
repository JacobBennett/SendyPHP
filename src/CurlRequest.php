<?php

namespace SendyPHP;

class CurlRequest implements HttpRequest
{
	private $handle = null;

	public function __construct() {
		$this->handle = curl_init();
	}

	function __destruct() {
		if ($this->handle != null) {
			$this->close();
		}
	}

	public function setOption($name, $value) {
		curl_setopt($this->handle, $name, $value);
	}

	public function execute() {
		return curl_exec($this->handle);
	}

	public function getInfo($name) {
		return curl_getinfo($this->handle, $name);
	}

	public function close() {
		curl_close($this->handle);
	}

	public function getErrorNumber() {
		return curl_errno($this->handle);
	}

	public function getErrorMessage() {
		return curl_error($this->handle);
	}
}

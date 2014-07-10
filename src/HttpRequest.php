<?php

namespace SendyPHP;

interface HttpRequest
{
	public function setOption($name, $value);
	public function execute();
	public function getInfo($name);
	public function close();
}

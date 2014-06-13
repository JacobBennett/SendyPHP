<?php

use SendyPHP\SendyPHP;

/**
 * 	@author : Gayan Hewa
 */
class SendyPHPTest extends PHPUnit_Framework_TestCase
{
	protected $config;

	public function setUp()
	{
		$this->config = array(
			'api_key' => 'xxx', //your API key is available in Settings
			'installation_url' => 'http://aaa.aaa.com',  //Your Sendy installation
			'list_id' => 'xxx'//Users - vEpmBm892Lq3bp1f8Ebzg0NQ' //Users list
		);
	}

	/**
	 * Test Subscribe status
	 * @return void
	 */
	public function test_failed_substatus()
	{
		$http_request = $this->getMock('HttpRequest', array('setOption', 'execute'));
		$http_request->expects($this->any())
			->method('setOption');
		$http_request->expects($this->once())
			->method('execute')
			->will($this->returnValue("Email does not exist in list"));

		$config = array_merge($this->config, array('http_request' => $http_request));
		$sendy = new SendyPHP($config);
		$result = $sendy->substatus('test@test.com');
		// $this->assertEquals($result['message'], 'Email does not exist in list');
		// $this->assertEquals($result['status'], false);
	}

	/**
	 * Subscribe new user test
	 * @return void
	 */
	public function test_subscribe()
	{
		$user =		array(
				        'name'=>'Gayan',
				        'email' => 'gayanhewa@gmail.com'
		          );

		$result = $this->sendy->subscribe($user);

		//var_dump($result);

		$this->assertEquals($result['message'], 'Subscribed');
		$this->assertEquals($result['status'], true);

	}

	/**
	 * Unsubscribe test
	 * @return void
	 */
	public function test_unsubscribe()
	{

		$result = $this->sendy->unsubscribe('gayanhewa@gmail.com');

		//var_dump($result);

		$this->assertEquals($result['message'], 'Unsubscribed');
		$this->assertEquals($result['status'], true);
	}

	public function test_subcount()
	{

		$result = $this->sendy->subcount();

		//var_dump($result);

		//Number of subscribesin the list
		$this->assertEquals($result['message'], '2');
	}

}
?>
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
			'api_key' => '',
			'installation_url' => '',
			'list_id' => 'asdf'
		);
	}

	protected function getSendyWithStubbedResponse($response) {
		$http_request = $this->getMock('HttpRequest', array('setOption', 'execute'));
		$http_request->expects($this->any())
			->method('setOption');
		$http_request->expects($this->once())
			->method('execute')
			->will($this->returnValue($response));

		$config = array_merge($this->config, array('http_request' => $http_request));
		$sendy = new SendyPHP($config);
		return $sendy;
	}

	/**
	 * Test Subscribe status
	 * @return void
	 */
	public function test_failed_substatus()
	{
		$sendy = $this->getSendyWithStubbedResponse("Email does not exist in list");
		$result = $sendy->substatus('test@test.com');
		$this->assertEquals($result['message'], 'Email does not exist in list');
		$this->assertEquals(false, $result['status']);
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

		$sendy = $this->getSendyWithStubbedResponse('1');
		$result = $sendy->subscribe($user);
		$this->assertEquals(true, $result['status']);
		$this->assertEquals($result['message'], 'Subscribed');
	}

	/**
	 * Unsubscribe test
	 * @return void
	 */
	public function test_unsubscribe()
	{
		$sendy = $this->getSendyWithStubbedResponse('1');
		$result = $sendy->unsubscribe('gayanhewa@gmail.com');
		$this->assertEquals(true, $result['status']);
		$this->assertEquals($result['message'], 'Unsubscribed');
	}

	public function test_subcount()
	{
		$sendy = $this->getSendyWithStubbedResponse('2');
		$result = $sendy->subcount();
		//Number of subscriptions in the list
		$this->assertEquals(true, $result['status']);
		$this->assertEquals('2', $result['message']);
	}

}

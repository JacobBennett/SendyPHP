<?php
use SendyPHP\SendyPHP;

/**
 * 	@author : Gayan Hewa
 */
class SendyPHPTest extends PHPUnit_Framework_TestCase
{
	protected $sendy;

	public function setUp()
	{

		$config = [
			'api_key' => 'xxx', //your API key is available in Settings
			'installation_url' => 'http://aaa.aaa.com',  //Your Sendy installation
			'list_id' => 'xxx'//Users - vEpmBm892Lq3bp1f8Ebzg0NQ' //Users list
		];

		$sendy = new SendyPHP($config);

		$this->sendy = $sendy;
	}

	/**
	 * Test Subscribe status
	 * @return void
	 */
	public function test_failed_substatus()
	{
		//test@test.com - does not exist
		$result = $this->sendy->substatus('test@test.com');

		//var_dump($result);

		$this->assertEquals($result['message'], 'Email does not exist in list');
		$this->assertEquals($result['status'], false);

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
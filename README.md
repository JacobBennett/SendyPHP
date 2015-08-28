SendyPHP
=================

A PHP class built to interface with the Sendy API ([http://sendy.co](http://sendy.co))

## Installation

### Using Composer

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `jacobbennett/sendyphp`.

	"require": {
		"jacobbennett/sendyphp": "1.2.*"
	}

Next, update Composer from the Terminal:

    composer update

### Old School (alternative option)

* Place SendyPHP.php into your file structure
* Include or require SendyPHP in the location you would like to utilize it

```php
	require('SendyPHP\src\SendyPHP.php');
```

#Usage

Create an instance of the class while passing in an array including your API key, installation URL, and the List ID you wish to work with.
```php

	$config = array(
		'api_key' => 'yourapiKEYHERE', //your API key is available in Settings
		'installation_url' => 'http://updates.mydomain.com',  //Your Sendy installation
		'list_id' => 'your_list_id_goes_here'
	);
	
	$sendy = new \SendyPHP\SendyPHP($config);
	
	//you can change the list_id you are referring to at any point
	$sendy->setListId("a_different_list_id");
```

#Methods
After creating a new instance of SendyPHP call any of the methods below 

##Return Values
The return value of any of these functions will include both a status, and a message to go with that status.

The status is a boolean value of `true` or `false` and the message will vary based on the type of action being performed.

```php
	//example of a succesful return value
	array(
		'status'=>true,
		'message'=>'Already Subscribed'
	)
	
	//example of a UNsuccesful return value
	array(
		'status'=>false,
		'message'=>'Some fields are missing.'
	)
```

I have commented and organized the code so as to be readable, if you have further questions on the status or messages being returned, please refer to the library comments.

##subscribe(array $values)

This method takes an array of `$values` and will attempt to add the `$values` into the list specified in `$list_id`

```php
	$results = $sendy->subscribe(array(
		'name'=>'Jim',
		'email' => 'Jim@gmail.com', //this is the only field required by sendy
		'customfield1' => 'customValue'
	));
```
__Note:__ Be sure to add any custom fields to the list in Sendy before utilizing them inside this library.
__Another Note:__ If a user is already subscribed to the list, the library will return a status of `true`. Feel free to edit the code to meet your needs.

##unsubscribe($email)

Unsubscribes the provided e-mail address (if it exists) from the current list.
```php
	$results = $sendy->unsubscribe('test@testing.com');
```

##substatus($email)

Returns the status of the user with the provided e-mail address (if it exists) in the current list.
```php
	$results = $sendy->substatus('test@testing.com');
```
__Note:__ refer to the code or see http://sendy.co/api for the types of return messages you can expect.

##subcount()

Returns the number of subscribers to the current list.
```php
	$results = $sendy->subcount();
```

##setListId($list_id) and getListId()

Change or get the list you are currently working with.
```php
	
	//set or switch the list id
	$sendy->setListId('another_list_id');
	
	//get the current list id
	echo $sendy->getListId();
```

#Unit tests
All unit tests are located under src/test directory. To run the tests type in the below from the project root.
```shell
		php vendor/bin/phpunit src/test/SendyPHPTest.php
```

Ensure that the API keys are setup for testing :
```php

		$config = [
			'api_key' => 'xxx', //your API key is available in Settings
			'installation_url' => 'http://my.sendy.installation.com',  //Your Sendy installation
			'list_id' => 'xxx'// List ID
		];
```

Sendy-PHP-Library
=================

A PHP class built to interface with the Sendy API


###Getting Started

* Place sendyLibrary.php into your file structure
* Include or require the sendyLibrary in the location you would like to utilize it

```php
	require('sendyLibrary.php');
```

#Usage

To use the library, create an instance of the class including the list_id you are interested in working with as a parameter.
```php

	$config = array(
	'api_key' => 'yourapiKEYHERE', //your API key is available in Settings
	'installation_url' => 'http://updates.mydomain.com',  //Your Sendy installation
	'list_id' => 'your_list_id_goes_here'
	);
	
	$sendy = new SendyLibrary($config);
	
	//you can change the list_id you are referring to at any point
	$sendy->list_id = "a_different_list_id";
```

#Methods
After creating a new instance of the SendyLibrary call any of the methods below 

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

Returns the number of subscribers to the current list
```php
	$results = $sendy->subcount();
```

##Getter and Setter methods

You can get or set the values of any of the private variables of the SendyLibrary class as follows.
```php
	$sendy = new SendyLibrary('your_sendy_list_id');
	
	//set the api_key value to something else
	$sendy->api_key = '123456test';
	
	//get the current sendy installation that you declared in the SendyLibrary.php
	echo $sendy->installation_url;
```

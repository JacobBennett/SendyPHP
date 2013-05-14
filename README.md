Sendy-PHP-Library
=================

A PHP class built to interface with the Sendy API


###Getting Started

* Place sendyLibrary.php into your file structure
* Include or require the sendyLibrary in the location you would like to utilize it

##Setup

Edit the sendyLibrary.php



/*FOR TESTING PURPOSES */

$sendy = new SendyLibrary('3Xni96Lrt2wdMYl5Zjq8927Q');
$sendy->list_id = "3Xni96Lrt2wdMYl5Zjq8927Q";
echo $sendy->installation_url;
echo $sendy->api_key;
echo $sendy->list_id;

$results['subscribe'] = $sendy->subscribe(array(
								'name'=>'Jim',
								'email' => 'Jim@gmail.com'
							));

$results['unsubscribe'] = $sendy->unsubscribe('Jake@gmail.com');

$results['substatus'] = $sendy->substatus('Jake@gmail.com');

$results['subcount'] = $sendy->subcount();

print_r($results);

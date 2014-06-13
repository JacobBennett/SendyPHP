<?php

namespace SendyPHP;

/**
 * Sendy Class
 */
class SendyPHP
{
    protected $installation_url;
    protected $api_key;
    protected $list_id;
    protected $http_request;
    
    public function __construct(array $config)
    {
        //error checking
        $list_id = @$config['list_id'];
        $installation_url = @$config['installation_url'];
        $api_key = @$config['api_key'];
        $this->http_request = @$config['http_request'];
        
        if (!isset($list_id)) {
            throw new \Exception("Required config parameter [list_id] is not set", 1);
        }
        
        if (!isset($installation_url)) {
            throw new \Exception("Required config parameter [installation_url] is not set", 1);
        }
        
        if (!isset($api_key)) {
            throw new \Exception("Required config parameter [api_key] is not set", 1);
        }

        $this->list_id = $list_id;
        $this->installation_url = $installation_url;
        $this->api_key = $api_key;
    }

    public function setListId($list_id)
    {
        if (!isset($list_id)) {
            throw new \Exception("Required config parameter [list_id] is not set", 1);
        }
        
        $this->list_id = $list_id;
    }

    public function getListId()
    {
        return $this->list_id;
    }

    public function subscribe(array $values)
    {
        $type = 'subscribe';

        //Send the subscribe
        $result = strval($this->buildAndSend($type, $values));

        //Handle results
        switch ($result) {
            case '1':
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

    public function unsubscribe($email)
    {
        $type = 'unsubscribe';
        
        //Send the unsubscribe
        $result = strval($this->buildAndSend($type, array('email' => $email)));

        //Handle results
        switch ($result) {
            case '1':
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

    public function substatus($email)
    {
        $type = 'api/subscribers/subscription-status.php';
        
        //Send the request for status
        $result = $this->buildAndSend($type, array(
            'email' => $email,
            'api_key' => $this->api_key,
            'list_id' => $this->list_id
        ));

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

    public function subcount($list = "")
    {
        $type = 'api/subscribers/active-subscriber-count.php';

        //handle exceptions
        if ($list== "" && $this->list_id == "") {
            throw new \Exception("method [subcount] requires parameter [list] or [$this->list_id] to be set.", 1);
        }

        //if a list is passed in use it, otherwise use $this->list_id
        if ($list == "") {
            $list = $this->list_id;
        }

        //Send request for subcount
        $result = $this->buildAndSend($type, array(
            'api_key' => $this->api_key,
            'list_id' => $list
        ));
        
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

    private function buildAndSend($type, array $values)
    {

        //error checking
        if (!isset($type)) {
            throw new \Exception("Required config parameter [type] is not set", 1);
        }
        
        if (!isset($values)) {
            throw new \Exception("Required config parameter [values] is not set", 1);
        }

        //Global options for return
        $return_options = array(
            'list' => $this->list_id,
            'boolean' => 'true'
        );

        //Merge the passed in values with the options for return
        $content = array_merge($values, $return_options);

        //build a query using the $content
        $postdata = http_build_query($content);

        $http_request = $this->getHttpRequest();
        $http_request->setOption(CURLOPT_URL, $this->installation_url .'/'. $type);
        $http_request->setOption(CURLOPT_HEADER, 0);
        $http_request->setOption(CURLOPT_RETURNTRANSFER, 1);
        $http_request->setOption(CURLOPT_FOLLOWLOCATION, 1);
        $http_request->setOption(CURLOPT_POST, 1);
        $http_request->setOption(CURLOPT_POSTFIELDS, $postdata);
        $result = $http_request->execute();

        return $result;
    }

    protected function getHttpRequest() {
        if (!$this->http_request) {
            $this->http_request = new CurlRequest;
        }
        return $this->http_request;
    }
}

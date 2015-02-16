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
    protected $verify_ssl = 1;
    protected $ca_file;

    public function __construct(array $config)
    {
        //error checking
        $list_id = @$config['list_id'];
        $installation_url = @$config['installation_url'];
        $api_key = @$config['api_key'];
        $verify_ssl = @$config['verify_ssl'];
        $ca_file = @$config['ca_file'];
        
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

        if(isset($verify_ssl))
            $this->verify_ssl = $verify_ssl;

        if(isset($ca_file))
            $this->ca_file = $ca_file;
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
        if (is_numeric($result)) {
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

        $ch = curl_init($this->installation_url .'/'. $type);

        if( ! empty($this->ca_file)) {
            curl_setopt($ch,CURLOPT_CAINFO,$this->ca_file);
        }

        if( ! $this->verify_ssl) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        } 
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

<?php

namespace NotificationChannels\Sens;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Telegram\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Post\PostFile;

class Sens
{
    /** @var HttpClient HTTP Client */
    protected $http;

    /** @var null|string Tokens. */
    protected $authkey = null;
    protected $service_secret = null;
    protected $service_id = null;
    /**
     * @param null            $token
     * @param HttpClient|null $httpClient
     */
    public function __construct($authkey,$service_secret,$service_id, HttpClient $httpClient = null)
    {
        $this->{"X-NCP-auth-key"} = $authkey;
	$this->{"X-NCP-service-secret"} = $service_secret;
	$this->serviceId = $service_id;

        $this->http = $httpClient;
    }

    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient()
    {
        return $this->http ?: $this->http = new HttpClient();
    }

   
    public function sendMessage($params)
    {
		if (empty( $this->{"X-NCP-auth-key"}) || empty($this->{"X-NCP-service-secret"}) || empty($this->serviceId))
		{
		    throw CouldNotSendNotification::NCPTokenNotProvided('Naver Cloud Platform Token Required');
		}

		$endPointUrl = 'https://api-sens.ncloud.com/v1/sms/services/'.$this->serviceId.'/messages';

		try {

		    $post_name = 'form_params';
            return $this->httpClient()->post($endPointUrl, [
                'headers'=>array('Content-Type'=>'application/json','X-NCP-auth-key'=>$this->{"X-NCP-auth-key"}, 'X-NCP-service-secret'=>$this->{"X-NCP-service-secret"}),
                'body'=>json_encode($params)
            ]);

		
			

		} catch (Exception $exception) {
		    throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
		} 
	    }
    }






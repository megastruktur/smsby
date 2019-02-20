<?php

namespace megastruktur;

use GuzzleHttp\Client as Client;

class SMSby {

  // Unique secret token.
  protected $token;
  // Alphaname is set in your admin area.
  protected $alphaname_id;
  
  protected $api_url = 'https://app.sms.by/';
  protected $api_prefix = 'api/';
  protected $api_version = 'v1';
  protected $client;

  function __construct($token, $alphaname_id = '') {
    
    $this->token = $token;
    if ($alphaname_id) {
      $this->alphaname_id = $alphaname_id;
    }
    
    $constructed_base_url = $this->api_url . $this->api_prefix . $this->api_version . '/';
    
    $this->client = new Client(['base_uri' => $constructed_base_url]);
  }

  /**
   * Make a request to the API.
   * @param string $cmd
   * @param string $method
   * @param array $params
   * @return object
   */
  private function request($cmd, $method = 'GET', $params = []) {
    
    $params['token'] = $this->token;
    
    $query = [
      'query' => $params,
    ];
    
    try {
      $response = $this->client->request($method, $cmd, $query);

      if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody());
        return $data;
      } else {
        return false;
      }
    
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }
  
  /**
   * Get amount of messages you still have.
   * @return object
   */
  public function getLimit() {
    return $this->request('getLimit', 'GET');
  }
  
  /**
   * 
   * @param string $phone without any special chars
   *  example: 375298887777
   * @param string $message
   * @return object
   */
  public function sendQuickSms($phone, $message) {
    
    $params = [
      'phone' => str_replace(['+', '-', ' ', '(', ')'], '', $phone),
      'message' => $message,
    ];
    
    if ($this->alphaname_id) {
      $params['alphaname_id'] = $this->alphaname_id;
    }
    
    return $this->request('sendQuickSms', 'GET', $params);
  }

}

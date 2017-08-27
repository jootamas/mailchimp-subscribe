<?php
 /**
  * MailChimp subscribe, unsubscribe and get user datas via MailChimp API 3.0
  *
  * https://github.com/jootamas/mailchimp-subscribe
  */
 class MailChimp {
  private $dataCenter;
  public function __construct(){
   /**
    * get data center from end of API key
    */
   $this->dataCenter = substr(MAILCHIMP_API_KEY, (strpos(MAILCHIMP_API_KEY, '-') + 1));
  }
  /**
   * Validate email address
   * @param   string      $email
   * @return  true|false
   */
  public function validateEmail($email){
   return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
  }
  /**
   * @param   string   $email             email address
   * @param   string   $forName           forname
   * @param   string   $lastName          lastname
   * @return  string   'address-invalid'  the email address is invalid
   *                   'address-exists'   address already exists on this list
   *          boolean  true               subscribe success
   *          object                      the return object contains error status code:
   *                                      https://developer.mailchimp.com/documentation/mailchimp/guides/error-glossary
   */
  public function subscribe($email, $forName = '', $lastName = ''){
   if(!$this->validateEmail(trim($email))){
    return array('status' => 'address-invalid');
   }
   if($this->getSubscriber(trim($email)) !== false){
    return array('status' => 'address-exists');
   }
   $data['apikey'] = MAILCHIMP_API_KEY;
   $data['email_address'] = trim($email);
   $data['status'] = 'subscribed';
   if(isset($forName)){
    $data['merge_fields']['FNAME'] = $forName;
   }
   if(isset($lastName)){
    $data['merge_fields']['LNAME'] = $lastName;
   }
   $json_data = json_encode($data);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'https://'.$this->dataCenter.'.api.mailchimp.com/3.0/lists/'.MAILCHIMP_LIST_ID.'/members');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: apikey '.MAILCHIMP_API_KEY));
   curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/1.0');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
   $result = json_decode(curl_exec($ch));
   if($result->status == 'subscribed'){
    return true;
   } else {
    return $result;
   }
  }
  /**
   * Subscribe user from the list
   * @param   string   $email  email address
   * @return  boolean  true    unsubscribe success
   *          object           the return object contains error status code:
   *                           https://developer.mailchimp.com/documentation/mailchimp/guides/error-glossary
   */
  public function unSubscribe($email){
   if(!$this->validateEmail(trim($email))){
    return array('status' => 'invalid-address');
   }
   if($this->getSubscriber(trim($email)) === false){
    return array('status' => 'address-no-exists');
   }
   $data['apikey'] = MAILCHIMP_API_KEY;
   $data['email_address'] = trim($email);
   $json_data = json_encode($data);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'https://'.$this->dataCenter.'.api.mailchimp.com/3.0/lists/'.MAILCHIMP_LIST_ID.'/members/'.md5($email));
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: apikey '.MAILCHIMP_API_KEY));
   curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/1.0');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
   $result = json_decode(curl_exec($ch));
   if(!$result){
    return true;
   } else {
    return $result;
   }
  }
  /**
   * Get datas of subscriber
   * @param   string   $email  email address
   * @return  boolean  false   email address no exists on this list
   *          object           the return object contains user datas
   */
  public function getSubscriber($email){
   if(!$this->validateEmail(trim($email))){
    return array('status' => 'invalid-address');
   }
   $data['apikey'] = MAILCHIMP_API_KEY;
   $data['email_address'] = trim($email);
   $json_data = json_encode($data);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'https://'.$this->dataCenter.'.api.mailchimp.com/3.0/lists/'.MAILCHIMP_LIST_ID.'/members/'.md5($email));
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: apikey '.MAILCHIMP_API_KEY));
   curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/1.0');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
   $result = json_decode(curl_exec($ch));
   if($result->status == 'subscribed'){
    return $result;
   } else {
    return false;
   }
  }
 }
?>

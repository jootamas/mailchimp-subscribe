<?php
 include_once('class-mailchimp-subscribe.php');
 
 define('MAILCHIMP_API_KEY', '[PUT_YOUR_API_KEY]');
 define('MAILCHIMP_LIST_ID', '[PUT_YOUR_LIST_ID]');
 
 $MailChimp = new MailChimp();
 
 /* subscribe new user */
 
 echo '<h1>Subscribe new user</h1>';
 
 $subscribeUser = $MailChimp->subscribe('myemail@mydomain.com', 'MyForname', 'MyLastname');
 
 if($subscribeUser === true){
  echo 'User subscribe success';
 } else {
  echo '<pre>';
   print_r($subscribeUser);
  echo '</pre>';
 }
 
 /* get user datas */
 
 echo '<h1>Get user datas</h1>';
 
 $getSubscribeUser = $MailChimp->getSubscriber('myemail@mydomain.com');
 
 if($getSubscribeUser === false){
  echo 'User does not exists';
 } else {
  echo 'email address: '.$getSubscribeUser->email_address.'<br />';
  echo 'forname: '.$getSubscribeUser->merge_fields->FNAME.'<br />';
  echo 'lastname: '.$getSubscribeUser->merge_fields->LNAME.'<br />';
  echo 'more:';
  echo '<pre>';
   print_r($getSubscribeUser);
  echo '</pre>';
 }
 
 /* unsubscribe user */
 
 echo '<h1>Unsubscribe user</h1>';
 
 $subscribeUser = $MailChimp->unSubscribe('myemail@mydomain.com');
 
 if($subscribeUser === true){
  echo 'User unsubscribe success';
 } else {
  print_r($subscribeUser);
 }
?>

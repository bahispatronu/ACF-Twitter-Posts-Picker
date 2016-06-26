<?php

require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterAPI
{

  private $twitter;
  private $consumer_key;
  private $consumer_secret;
  private $access_token;
  private $access_secret;

  public function __construct($consumer_key, $consumer_secret, $access_token, $access_secret)
  {
    $this->consumer_key = $consumer_key;
    $this->consumer_secret = $consumer_secret;
    $this->access_token = $access_token;
    $this->access_secret = $access_secret;

    $this->twitter = new TwitterOAuth(
      $this->consumer_key,
      $this->consumer_secret,
      $this->access_token,
      $this->access_secret
    );

  }

  public function getPosts($keyword)
  {
    return $this->twitter->get("search/tweets", ["q" => $keyword]);

  }

}

?>
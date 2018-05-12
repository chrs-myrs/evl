<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::library('twitteroauth/twitteroauth', 'tweetcrete');
Loader::model('tweetcrete_tweet','tweetcrete');

class TweetcreteFetchBase
{
  private $status_api_method  = "";
  private $twitterObj         = null;
  private $oAuthToken         = "";
  private $oAuthTokenSecret   = "";

  public function __construct($oAuthToken, $oAuthTokenSecret) {
    $this->oAuthToken = $oAuthToken;
    $this->oAuthTokenSecret = $oAuthTokenSecret;
    $tweetcrete_rest = new TweetcreteRest($this->oAuthToken, $this->oAuthTokenSecret);
    $this->twitterObj = $tweetcrete_rest->getTwitterOAuthObject();
  }
  
  
}
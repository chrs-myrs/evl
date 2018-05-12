<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::library('twitteroauth/twitteroauth', 'tweetcrete');
class TweetcreteRest extends Object{
  private $TwitterConsumerKey     = 'rhAI8BoeHpX75eg2Eu1UqQ';
  private $TwitterConsumerSecret  = 'B31561bDXMmXAvhcRJr4o4hPwEXBHTkqak3NYTaAbE';
  private $oAuthToken                   = "";
  private $oAuthTokenSecret             = "";
  private $twitterOAuthObject           = null;
  
  public function __construct($oAuthToken, $oAuthTokenSecret) {
    $this->oAuthToken = $oAuthToken;
    $this->oAuthTokenSecret = $oAuthTokenSecret;
  }
  
  public function getTwitterOAuthObject() {
    $twitterOAuthObject = null;
    if( $this->twitterOAuthObject ) {
      $twitterOAuthObject = $this->twitterOAuthObject;
    } else {
      if($this->oAuthToken && $this->oAuthTokenSecret) {
        $this->twitterOAuthObject = new TweetcreteTwitterOAuth($this->TwitterConsumerKey, $this->TwitterConsumerSecret, $this->oAuthToken, $this->oAuthTokenSecret);
        $twitterOAuthObject = $this->twitterOAuthObject;
      } else {
        $twitterOAuthObject = new TweetcreteTwitterOAuth($this->TwitterConsumerKey, $this->TwitterConsumerSecret);
      }
    }
    return $twitterOAuthObject;
  }

}
<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::library('twitteroauth/twitteroauth', 'tweetcrete');
Loader::model('tweetcrete_tweet','tweetcrete');

class TweetcreteUserTimeline
{
  private $screen_name        = "";
  private $api_method  = "statuses/user_timeline";
  public  $tweetCount         = 100;
  public  $showRetweets       = false;
  public  $excludeReplies     = false; 
  private $twitterObj         = null;
  private $oAuthToken         = "";
  private $oAuthTokenSecret   = "";

  public function __construct($oAuthToken, $oAuthTokenSecret) {
    $this->oAuthToken = $oAuthToken;
    $this->oAuthTokenSecret = $oAuthTokenSecret;
    $tweetcrete_rest = new TweetcreteRest($this->oAuthToken, $this->oAuthTokenSecret);
    $this->twitterObj = $tweetcrete_rest->getTwitterOAuthObject();
  }

  public function getTimeline($screen_name) {    
    $this->screen_name = $screen_name;
    return $this->toTweetObjects( $this->getRawTimeline() );
  }
  
  private function getRawTimeline() {
    $params = array();
    if(strlen($this->screen_name)) $params = array('screen_name' => $this->screen_name, 'count' => $this->tweetCount, 'include_rts' => $this->includeRetweets(), 'exclude_replies' => $this->excludeReplies());
    $tweets = $this->twitterObj->get($this->api_method, $params);
    return $tweets;
  }
  
  private function includeRetweets() {
    return $this->showRetweets ? 'true' : 'false';
  }
  
  private function excludeReplies() {
    return $this->excludeReplies ? 'true' : 'false';
  }

  private function toTweetObjects($oauth_tweets) {
    $tweet_array = array();
    foreach($oauth_tweets as $oauth_tweet) {
      $tweet_array[]= TweetcreteTweet::fromOAuth($oauth_tweet);
    }
    
    return $tweet_array;
  }
}
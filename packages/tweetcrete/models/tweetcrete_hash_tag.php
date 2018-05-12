<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

class TweetcreteHashTag {
  private $hashtag            = "";
  private $api_method         = "search/tweets";
  private $twitterObj         = null;
  private $oAuthToken         = "";
  private $oAuthTokenSecret   = "";

  public function __construct($oAuthToken, $oAuthTokenSecret) {
    $this->oAuthToken = $oAuthToken;
    $this->oAuthTokenSecret = $oAuthTokenSecret;
    $tweetcrete_rest = new TweetcreteRest($this->oAuthToken, $this->oAuthTokenSecret);
    $this->twitterObj = $tweetcrete_rest->getTwitterOAuthObject();
  }

  public function getTimeline($hashTag) {
    $this->hashtag = $hashTag;
    
    return $this->toTweetObjects( $this->getRawTimeline() );
  }
  
  private function getRawTimeline() {
    $params = array();
    if(strlen($this->hashtag)) $params = array('q' => sprintf("%%23%s",$this->hashtag));
    $tweets = $this->twitterObj->get($this->api_method, $params);
    return $tweets;
  }

  private function toTweetObjects($oauth_tweets) {
    $tweet_array = array();
    foreach($oauth_tweets->statuses as $oauth_tweet) {
      $tweet_array[]= TweetcreteTweet::fromJSON($oauth_tweet);
    }
    
    return $tweet_array;
  }
}
<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
Loader::model('tweetcrete_tweet', 'tweetcrete');

class TweetcreteTweet {
  public $id          = 0;
  public $author      = "";
  public $content     = "";
  public $timestamp   = "";
  public $avatar_url  = "";
  public $retweeted   = false;
  public $is_retweet  = false;
  public $is_reply    = false;
  public $to_user     = "";
  public $sort_key    = 0;
  
  static function fromJSON($json) {
    $tweet = new TweetcreteTweet();
    
    $tweet->id          = $json->id;
    $tweet->author      = $json->user->screen_name;
    $tweet->content     = $json->text;
    $tweet->timestamp   = $json->created_at;
    $tweet->sort_key    = strtotime($tweet->timestamp);
    $tweet->avatar_url  = $json->user->profile_image_url;
    $tweet->to_user     = $json->in_reply_to_screen_name;

    return $tweet;
  }

  static function fromOAuth($oauth_tweet) {
    $tweet = new TweetcreteTweet();
    
    $tweet->id          = $oauth_tweet->id;
    $tweet->author      = $oauth_tweet->user->screen_name;
    $tweet->content     = $oauth_tweet->text;
    $tweet->timestamp   = $oauth_tweet->created_at;
    $tweet->sort_key    = strtotime($tweet->timestamp);
    $tweet->avatar_url  = $oauth_tweet->user->profile_image_url;
    $tweet->to_user     = $oauth_tweet->in_reply_to_screen_name;
    $tweet->retweeted   = $oauth_tweet->retweeted;
    $tweet->is_reply    = !empty($oauth_tweet->in_reply_to_user_id_str);
     
    return $tweet;
  }

  /*
   * Formats a passed in timestamp based on dateFormat setting
   */
  function formatTimestamp($format) {
    return date($format, $this->timestampFromEpoch());
  }
  
  function timestampFromEpoch() {
    return strtotime($this->timestamp);
  }
}
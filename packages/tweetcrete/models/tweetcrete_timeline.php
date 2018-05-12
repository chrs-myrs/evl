<?php  
Loader::model('tweetcrete_hash_tag', 'tweetcrete');
Loader::model('tweetcrete_user_timeline', 'tweetcrete');
Loader::library('cache');
defined('C5_EXECUTE') or die(_("Access Denied."));

class TweetcreteTimeline {
  protected $timeline                 = array();
  protected $tweetcrete_hashtag       = null;
  protected $tweetcrete_user_timeline = null;
  public $oauth_token                 = null;
  public $oauth_token_secret          = null;
  public $show_retweets               = false;
  public $show_replies                = false;
  public $tweet_count                 = 100;
  
  public function __construct($oauth_token, $oauth_token_secret) {
    $this->oauth_token = $oauth_token;
    $this->oauth_token_secret = $oauth_token_secret;
  }

  public function getTimeline() {
    if( empty( $this->timeline ) ) {
      return array();
    } else {
      $this->sortTimeline();
      return $this->timeline;
    }
  }
  
  
  public function loadUser($screen_name) {
    $user_timeline = $this->tweetcreteUserTimelineObj()->getTimeline($screen_name);
    if( empty($user_timeline) ) {
      $user_timeline = $this->getTimelineCache('user', $screen_name);
    } else {
      $this->setTimelineCache('user', $screen_name, $user_timeline);
    }
    $this->addTweetsToTimeline( $user_timeline );
  }
  
  
  public function loadHashTag($hash_tag) {
    $hashtag_timeline = $this->tweetcreteHashTagObj()->getTimeline($hash_tag);
    
    if( empty($hashtag_timeline) ) {
      $hashtag_timeline = $this->getTimelineCache('hashtag', $hash_tag);
    } else {
      $this->setTimelineCache('hashtag', $hash_tag, $hashtag_timeline);
    }
    
    $this->addTweetsToTimeline( $hashtag_timeline );
  }
  
  private function addTweetToTimeline($tweetObj) {
    $timeline[] = $tweetObj;
  }
  
  private function addTweetsToTimeline($tweetArr) {
    if( is_array($tweetArr) ) {
      $this->timeline = array_merge($this->timeline,$tweetArr);
    }
  }
  
  private function sortTimeline() {
    $timelineSortFunction = create_function('$a, $b', 'if( $a->sort_key == $b->sort_key ) return 0; return ( $a->sort_key > $b->sort_key ) ? -1 : 1;');
    usort($this->timeline, $timelineSortFunction);
  }
  
  private function tweetcreteHashTagObj() {
    if( ! $this->tweetcrete_hashtag ) {
      $this->tweetcrete_hashtag = new TweetcreteHashTag($this->oauth_token, $this->oauth_token_secret);
    }
    
    return $this->tweetcrete_hashtag;
  }

  private function tweetcreteUserTimelineObj() {
    if( ! $this->tweetcrete_user_timeline ) {
      $this->tweetcrete_user_timeline = new TweetcreteUserTimeline($this->oauth_token, $this->oauth_token_secret);
      $this->tweetcrete_user_timeline->tweetCount = $this->tweetCount();
      $this->tweetcrete_user_timeline->showRetweets = $this->showRetweets();
      $this->tweetcrete_user_timeline->excludeReplies = $this->excludeReplies();
    }
    
    return $this->tweetcrete_user_timeline;
  }
  
  private function setTimelineCache($type, $key, $timeline) {
    Cache::set('Tweetcrete', $this->buildCacheKey($type, $key) , $timeline);
  }

  private function getTimelineCache($type, $key) {
    return Cache::get('Tweetcrete', $this->buildCacheKey($type, $key));
  }
  
  private function buildCacheKey($type, $key) {
    return $type . $key;
  }
  
  private function showRetweets() {
    return $this->show_retweets ? true : false;
  }

  private function excludeReplies() {
    return $this->show_replies ? false : true;
  }
  /* Various options can result in there not being enough tweets in the result timeline once unqualified tweets are removed */  
  private function tweetCount() {
    if($this->tweet_count < 5) {
      $factor = 10;
    } elseif($this->tweet_count < 25) {
      $factor = 5;
    } elseif($this->tweet_count < 50) {
      $factor = 2.5;
    } else {
      $factor = 1.5;
    }
    return ceil((int)$this->tweet_count * $factor);
  }
}

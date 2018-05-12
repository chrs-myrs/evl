<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));

  Loader::model('tweetcrete_rest','tweetcrete');
  Loader::model('tweetcrete_tweet','tweetcrete');
  Loader::model('tweetcrete_timeline','tweetcrete');
  Loader::library('twitteroauth/twitteroauth', 'tweetcrete');


  class TweetcreteBlockController extends BlockController {
    protected $btTable = 'btTweetcrete';
    protected $btInterfaceWidth = "400";
    protected $btInterfaceHeight = "420";

    protected $TwitterConsumerKey     = 'rhAI8BoeHpX75eg2Eu1UqQ';
    protected $TwitterConsumerSecret  = 'B31561bDXMmXAvhcRJr4o4hPwEXBHTkqak3NYTaAbE';

    protected $twitterOAuthObject = null;

    /*
     * Block description with localization support
     */
    public function getBlockTypeDescription() {
      return t("Add a Twitter feed to any page");
    }

    /*
     * Block name with localization support
     */
    public function getBlockTypeName() {
      return t("Tweetcrete");
    }

    public function view() {      
      $twitterTimeline = $this->getTimeline();
      $twitterTimeline = $this->filterTimeline($twitterTimeline);
      $twitterUserCredentials = $this->getUserCredentials();

      $this->set('twitterTimeline', $twitterTimeline);
      $this->set('twitterProfileImage', $twitterUserCredentials->profile_image_url);
      $this->set('twitterUserName', $this->userName);
      $this->set('timelineComponents', $this->getTimelineComponentsForDisplay());
    }

    function save($args) {
      /* Flush any existing Timeline Components */
      $this->deleteExistingUserOrHashSettings(); //if any exist, we remove them
      /* Flush the cache */
      $this->flushFullTimelineCache();

      /* Fix the checkbox fields */
      $args = $this->fixCheckboxFields($args);

      $userOrHash = empty($_POST['userOrHash']) ? array() : $_POST['userOrHash'];
      $userOrHashValues = empty($_POST['userOrHashValue']) ? array() : $_POST['userOrHashValue'];;

      /*
       * Get the tokens and setup the default Timeline Component if user is
       * authenticating or reauthenticating a Twitter account.
       */
      if(isset($_POST['oAuthPIN'])) {
        $twitterObj = $this->getTwitterOAuthObject($_POST['oAuthRequestToken'], $_POST['oAuthRequestTokenSecret']);

        $access_token = $twitterObj->getAccessToken($_POST['oAuthPIN']);
        $args['oAuthToken']       = $access_token['oauth_token'];
        $args['oAuthTokenSecret'] = $access_token['oauth_token_secret'];
        $args['userName']         = $access_token['screen_name'];
        $args['userID']           = $access_token['user_id'];
        
        array_unshift($userOrHash, 1);
        array_unshift($userOrHashValues, $args['userName']);
      }

      if(!empty($userOrHash) && !empty($userOrHashValues)){
        $userOrHashSettingsArray = array();
        /* Combine the two arrays eliminating any items where either array value is null */
        foreach($userOrHash as $post_array_key => $post_array_value){
          if(!empty($post_array_value) && !empty($userOrHashValues[$post_array_key])) {
            array_push($userOrHashSettingsArray, array($post_array_value => $userOrHashValues[$post_array_key]));
          }
        }
        $this->saveUserOrHashSettingsArray($userOrHashSettingsArray);
      }

      parent::save($args);
    }

    public function validate() {
      $validation = Loader::helper('validation/form');

      $form_data = $this->post();
      $validation->setData($form_data);

      // Basic validation
      $validation->addInteger('timelineCacheTTL', t('Cache live time must be numeric.'), false);
      $validation->addRequired('dateFormat', t('Date format cannot be blank.'));
      $validation->test();

      $validation_error = $validation->getError();

      // Advanced validation
      // Make sure the tweet limit is something > 0
      if((int)$form_data['displayLimit'] <= 0) {
        $validation_error->add(t('Tweet limit must be an integer greater than 0.')); 
      }

      // Ensure they added at least one timeline component
      if( empty($form_data['userOrHash']) || empty($form_data['userOrHashValue']) ) {
        $validation_error->add(t('You must add at least one timeline component.'));
      }
      return $validation_error;
    }

    private function saveUserOrHashSettingsArray(array $a){
      if(!empty($a)){
        $db = Loader::db();
        foreach($a as $setting){
          foreach($setting as $settingType => $settingValue){
            //remove any # or @
            $settingValue = str_replace(array('#','@'),'',$settingValue);
            $db->query("INSERT INTO btTweetcreteUserOrHashSettings (bID,type,value) values (?,?,?)", array($this->bID,$settingType,$settingValue));
          }
        }
      }
    }
    
    private function deleteExistingUserOrHashSettings(){
      $db = Loader::db();
      $db->execute("DELETE FROM btTweetcreteUserOrHashSettings where bID = ?",array($this->bID)); //what a table name!
    }
    
    function getUserOrHashSettingsArray(){
      $db = Loader::db();
      $result = array();
      $db_result = $db->getAll("SELECT * from btTweetcreteUserOrHashSettings where bID = ? ORDER BY assertOrder ASC",array($this->bID));
      if(!empty($db_result)) foreach($db_result as $d){
        array_push($result,$d);
      }
      return $result;
    }

    /*
     * Filters timeline based on display settings
     */
    function filterTimeline($twitterTimeline) {
      $filteredTwitterTimeline = array();

      if(count($twitterTimeline) > 0) {
        foreach($twitterTimeline as $twitterStatusUpdate) {
          if(($this->displayLimit > $i) &&
             ($this->showReplies || !$twitterStatusUpdate->is_reply) &&
             ($this->showRetweets || !$twitterStatusUpdate->is_retweet) ) {
            array_push($filteredTwitterTimeline, $twitterStatusUpdate);
            $i++;
          } elseif( $this->displayLimit <= $i ) {
            break;
          }
        }
      }

      return $filteredTwitterTimeline;
    }
    
    public function getTimeline() {
      $timeline = Cache::get('Tweetcrete', $this->fullTimelineCacheKey());
      if( empty( $timeline ) ) {
        $tweetcreteTimeline = new TweetcreteTimeline($this->oAuthToken, $this->oAuthTokenSecret);
        $tweetcreteTimeline->show_retweets = ($this->showRetweets == 1);
        $tweetcreteTimeline->show_replies = ($this->showReplies == 1);
        $tweetcreteTimeline->tweet_count = $this->displayLimit;

        $timeline_components = $this->getUserOrHashSettingsArray();

        if( !empty($timeline_components) ) {
          foreach( $timeline_components as $timeline_component ) {
            if( (int)$timeline_component['type'] == 1 ) {
              $tweetcreteTimeline->loadUser($timeline_component['value']);
            }elseif( (int)$timeline_component['type'] == 2 ) {
              $tweetcreteTimeline->loadHashTag($timeline_component['value']);
            }
          }
        }
        $timeline = $tweetcreteTimeline->getTimeline();

        if( !empty($timeline) ) {
          Cache::set('Tweetcrete', $this->fullTimelineCacheKey(), $timeline, $this->fullTimelineCacheTTL());
        }
      }
      
      return $timeline;
    }
    
    private function fullTimelineCacheKey() {
      return "FullTimline-" . $this->bID;
    }
    
    private function fullTimelineCacheTTL() {
      return (int)$this->timelineCacheTTL;
    }
    
    private function flushFullTimelineCache() {
      Cache::delete('Tweetcrete', $this->fullTimelineCacheKey());
    }
 
    public function getTimelineComponentsForDisplay() {
      $arr = $this->getUserOrHashSettingsArray();
      $hashArray = array();
      if(!empty($arr)) {
        foreach($arr as $a){
          $component_string = "";
          if((int)$a['type'] == 1) {
            $component_string = "@" . $a['value'];
          } elseif((int)$a['type'] == 2) {
            $component_string = "#" . $a['value'];
          }
          array_push($hashArray, $component_string);
        }
      }
      return $hashArray;
    }   

    /*
     * Returns user credentials
     */
    public function getUserCredentials() {
      $twitterObj = $this->getTwitterOAuthObject($this->oAuthToken, $this->oAuthTokenSecret);

      return $twitterObj->get('account/verify_credentials');
    }

    /*
     * Returns twitteroauth object, caches it in instances where token is already established
     */
    public function getTwitterOAuthObject($token = FALSE, $token_secret = FALSE) {
      $twitterOAuthObject = null;
      if( $this->twitterOAuthObject ) {
        $twitterOAuthObject = $this->twitterOAuthObject;
      } else {
        $tweetcrete_rest = new TweetcreteRest($token, $token_secret);
        $twitterOAuthObject = $tweetcrete_rest->getTwitterOAuthObject();
        $this->twitterOAuthObj = $twitterOAuthObject;
      }
      return $twitterOAuthObject;
    }
    
    /*
     * Ensures that all checkbox fields get set to either 1 or 0 on the edit block form
     */
    private function fixCheckboxFields($args) {
      $checkbox_fields = array('showAvatar', 'showAvatarTimeline', 'showUsername', 'showAuthorTimeline', 'showTimestamp', 'showTimestampInWords', 'showFollowLink', 'showReplies', 'showRetweets', 'showHashAndUserData');
      foreach($checkbox_fields as $fieldname) {
        $args[$fieldname] = $args[$fieldname] == '1' ? 1 : 0;
      }
      
      return $args;
    }

  }

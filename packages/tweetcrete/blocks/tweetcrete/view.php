<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));
  $twitterHelper = Loader::helper('twitter','tweetcrete');
?>

<div class="ccm-tweetcrete">
  <div class="ccm-tweetcrete-timeline">
    <?php  
      if( $controller->showAvatar ) {
        printf('<img src="%s" width="48" height="48" alt="%s"  class="ccm-tweet-avatar"/>', $twitterProfileImage, $twitterUserName);
      }
  
      if( $controller->showUsername ) {
        printf('<h1 class="ccm-tweet-username">@%s</h1>', $twitterUserName);
      }
  
      if( $controller->showFollowLink ) {
        printf('<a href="http://twitter.com/%s" class="ccm-tweet-follow-link" target="new">%s</a>', $twitterUserName, t('follow me'));
      }
    ?>
  </div>
  
  <?php   if($showHashAndUserData == 1){ ?>
    <div class="ccm-tweetcrete-timeline-elements">
      <h4><?php   echo t('Following') ?></h4>
      <?php   if( !empty($timelineComponents) ) { ?>
        <?php   if(!empty($timelineComponents)){ ?>
          <div class="ccm-tweetcrete-timeline-meta">
            <span><?php   echo $twitterHelper->linkContent(implode(' , ', $timelineComponents )) ?></span>
          </div>
        <?php   } //end empty hash tags ?>
      <?php   } else { ?>
        <em><?php   echo t('nothing to follow') ?></em>
      <?php   } ?>
    </div>
  <?php   } //end of check on showHashAndUserData ?>

  <div class="ccm-tweetcrete-timeline">
    <ul>
      <?php   if(empty($twitterTimeline)) { ?>
        <li><em><?php   echo t('No tweets found.') ?></em></li>
      <?php   } else { ?>
        <?php   foreach($twitterTimeline as $tweet) { ?>
          <li>
            <?php   if ($showAvatarTimeline) { ?>
              <div class="ccm-tweet-avatar">
                <img src="<?php   echo $tweet->avatar_url ?>" />
              </div>
            <?php   } ?>
            <?php   if( $showTimestamp ) { ?>
              <?php   if( $showTimestampInWords ) { ?>
                <div class="ccm-tweet-time"><?php   echo $twitterHelper->agoInWords($tweet->timestampFromEpoch()) ?></div>
              <?php   } else { ?>
              <div class="ccm-tweet-time"><?php   echo $tweet->formatTimestamp($dateFormat) ?></div>
              <?php   } ?>
            <?php   } ?>
            <div class="ccm-tweet-text"><?php   echo $twitterHelper->linkContent($tweet->content) ?>
              <?php   if( $showAuthorTimeline ) { ?>
                <span class="posted-by">
                  <?php   echo t('via @%s',$tweet->author) ?>
                </span>
              <?php   } ?>
            </div>
            <div style="clear:both;"></div>
          </li>
        <?php   } ?>
      <?php   } ?>
    </ul>
  </div>
</div>  

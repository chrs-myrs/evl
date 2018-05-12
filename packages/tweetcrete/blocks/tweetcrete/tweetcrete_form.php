<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));

  $form = Loader::helper('form');
  $twitterObj = $controller->getTwitterOAuthObject();

  $twitterRequestTokenArray = $twitterObj->getRequestToken();
  $twitterRequestToken        = $twitterRequestTokenArray['oauth_token'];
  $twitterRequestTokenSecret  = $twitterRequestTokenArray['oauth_token_secret'];
  $userTimelineOrHashSelectArray = array(1 => t('User Timeline'), 2=> t('Hash Tag'));
  $existingSettings = $this->controller->getUserOrHashSettingsArray();
?>

<div class="ccm-block-field-group">
  <p><strong><?php   echo t('Authenticate') ?></strong></p>
  <?php   echo $form->hidden('userName', $userName) ?>
  <?php   echo $form->hidden('oAuthRequestToken', $twitterRequestToken) ?>
  <?php   echo $form->hidden('oAuthRequestTokenSecret', $twitterRequestTokenSecret) ?>

  <?php   if( strlen($oAuthToken) > 0 ) { ?>
    <?php   echo $form->hidden('userID', $userID) ?>
    <?php   echo $form->hidden('oAuthToken', $oAuthToken) ?>
    <?php   echo $form->hidden('oAuthTokenSecret', $oAuthTokenSecret) ?>
    <p>
      <em><?php   echo t('Connected to:') ?></em> <a href="http://twitter.com/<?php   echo $userName ?>" target="_blank"><?php   echo $userName ?></a>
      <?php   printf('<a href="%s" target="_blank" onclick="showTwitterAuthPIN();">%s</a>', $twitterObj->getAuthorizeURL($twitterRequestToken), t('Re-Authorize with Twitter')) ?>
    </p>
  <?php   } else { ?>
    <p><?php   printf('<a href="%s" target="_blank" onclick="showTwitterAuthPIN();">%s</a>', $twitterObj->getAuthorizeURL($twitterRequestToken), t('Authorize with Twitter')) ?></p>
  <?php   } ?>
  <label for="oAuthPIN" id="ccm-twitter-oauth-pin-label" style="display: none;"><?php   echo t('Twitter Authentication PIN') ?></label>
  <input type="text" size="6" id="ccm-twitter-oauth-pin" name="oAuthPIN" disabled="disabled" style="display: none;" />
</div>

<div class="ccm-block-field-group">
  <h4><?php   echo t('Display Options') ?></h4>
  <table width="375" cellpadding="0" cellspacing="5" border="0">
    <tbody>
      <tr>
        <th><?php   echo $form->label('dateFormat', t('Date format')) ?></th>
		<td><select name="dateFormat">
				<option value="F j, Y" <?php  if ($dateFormat == 'F j, Y') echo 'selected="selected"';?>><?php  echo t('Month Day, Year');?></option>
				<option value="Y" <?php  if ($dateFormat == 'Y') echo 'selected="selected"';?>><?php  echo t('Year');?></option>
				<option value="D" <?php  if ($dateFormat == 'D') echo 'selected="selected"';?>><?php  echo t('Day');?></option>
				<option value="g:i a" <?php  if ($dateFormat == 'g:i a') echo 'selected="selected"';?>><?php  echo t('Time');?></option>
				<option value="D, g:i a" <?php  if ($dateFormat == 'D, g:i a') echo 'selected="selected"';?>><?php  echo t('Day, Time');?></option>
				<option value="g:i a, F j, Y" <?php  if ($dateFormat == 'g:i a, F j, Y') echo 'selected="selected"';?>><?php  echo t('Time, Month Day, Year');?></option>
			</select>
		</td>
	</div>
	  
	  </tr>
      <tr>
        <th><?php   echo $form->label('displayLimit', t('Show how many tweets?')) ?></th>
        <td><input type="text" size="2" name="displayLimit" value="<?php   echo ($displayLimit ? $displayLimit : 3) ?>" /></td>
      </tr>
      <tr>
        <th><?php   echo $form->label('timelineCacheTTL', t('Cache live time?  (in seconds)')) ?></th>
        <td><input type="text" size="2" name="timelineCacheTTL" value="<?php   echo $timelineCacheTTL ?>" /></td>
      </tr>
    </tbody>
  </table>
  <br />
  <table class="ccm-grid" width="375" cellpadding="0" cellspacing="0" border="0">
    <tbody>
      <tr>
        <th><?php   echo t('Display Element') ?></th>
        <th><?php   echo t('In Header') ?></th>
        <th><?php   echo t('In Timeline') ?></th>
      </tr>
      <tr>
        <td><?php   echo t('Avatar') ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showAvatar', 1, $showAvatar) ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showAvatarTimeline', 1, $showAvatarTimeline) ?></td>
      </tr>
      <tr class="ccm-row-alt">
        <td><?php   echo t('Screen Name') ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showUsername', 1, $showUsername) ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showAuthorTimeline', 1, $showAuthorTimeline) ?></td>
      </tr>
      <tr>
        <td><?php   echo t('Timestamp') ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showTimestamp', 1, $showTimestamp) ?></td>
      </tr>
      <tr>
        <td><?php   echo t('Timestamp in Words') ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showTimestampInWords', 1, $showTimestampInWords) ?></td>
      </tr>
      <tr class="ccm-row-alt">
        <td><?php   echo t('Follow Button') ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showFollowLink', 1, $showFollowLink) ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
      </tr>
      <tr>
        <td><?php   echo t('Replies') ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showReplies', 1, $showReplies) ?></td>
      </tr>
      <tr class="ccm-row-alt">
        <td><?php   echo t('Retweets') ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showRetweets', 1, $showRetweets) ?></td>
      </tr>
      <tr>
        <td><?php   echo t('Timeline Component Summary') ?></td>
        <td class='ccm-grid-cb'><?php   echo $form->checkbox('showHashAndUserData', 1, $showHashAndUserData) ?></td>
        <td class='ccm-grid-cb'>&nbsp;</td>
      </tr>
    </tbody>
  </table>
 
  <p><strong><?php   echo t('Timeline Components') ?></strong></p>
  <div id='tweetcrete-timeline-components'>
    <?php   
    if(!empty($existingSettings)) {
      $numSettings = count($existingSettings);
      $settingsIndex = 1;
      foreach($existingSettings as $setting) {
        $lastSetting = $settingsIndex == $numSettings ? true : false;
        $settingsIndex++;
        echo Loader::packageElement('hash_or_user_tweetcrete_form_element', 'tweetcrete',  array_merge($setting, array('userTimelineOrHashSelectArray' => $userTimelineOrHashSelectArray, "divClassOverride" => 'tweetcrete-existing-hash-or-user', 'lastSetting' => $lastSetting)));
      }
    }
    ?>
    <span id="append-components-anchor"></span>
  </div>
  <a href="#" class="add-timeline-component" style="text-decoration: none;" ><img src="<?php   echo $this->getBlockUrl() ?>/images/add.png" alt='<?php   echo t("add"); ?>' title='<?php   echo t("add"); ?>' width='16' height='16' style='vertical-align: middle;' /> <?php   echo t('add component') ?></a>

	<div style="display:none;" id="tweetcrete-component-row-template">
		<?php   echo Loader::packageElement('hash_or_user_tweetcrete_form_element', 'tweetcrete',array('userTimelineOrHashSelectArray' => $userTimelineOrHashSelectArray)); ?>
	</div>
</div>
<p>&nbsp;</p>

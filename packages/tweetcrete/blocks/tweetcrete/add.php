<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));
?>

<div class="ccm-tweetcrete-add-feed">
  <p>
    <?php   echo t('To add a Twitter feed to your site you must first establish a link with your Twitter account by granting the Twitter block access.') ?>
  </p>
  <?php   $this->inc('tweetcrete_form.php', array('adding' => 1)) ?>
</div>
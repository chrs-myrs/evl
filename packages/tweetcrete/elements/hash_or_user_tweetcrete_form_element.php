<?php  
  $classRequired = ($divClassOverride) ? $divClassOverride : 'user-or-hash-wrapper';
  $form = new FormHelper();
?>
<div class="<?php  echo $classRequired ?>">
  <?php   echo $form->select('userOrHash[]', $userTimelineOrHashSelectArray, $type) ?>
  <?php   echo $form->text('userOrHashValue[]', $value ,array('style'=>'size:40;')) ?>
  <a class="setting-remove"><img src="<?php   echo $this->getBlockUrl() ?>/images/delete.png" alt='<?php   echo t("delete"); ?>' title='<?php   echo t("delete"); ?>' width='16' height='16' style='vertical-align: middle;' /></a>
  <?php   if( false && $lastSetting ) { ?>
    <a href="#" class="add-timeline-component" id="add-timeline-component-button"><img src="<?php   echo $this->getBlockUrl() ?>/images/add.png" alt='<?php   echo t("add"); ?>' title='<?php   echo t("add"); ?>' width='16' height='16' style='vertical-align: middle;' /></a>
  <?php   } ?>
</div>

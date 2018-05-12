<?php        
$pkg = Package::getByHandle('flickrcrete');
$api_key = $pkg->config('FLICKRCRETE_API_KEY');
if($api_key == ''){
	echo t('It looks like you are missing the api key. Please go to: ');
	echo '<a href="'.DIR_REL.'index.php/dashboard/flickrcrete">Dashboard->Flickrcrete Settings</a>';
}
else {
?>
<script language="javascript">
$(function() {
	if($('#method').val() == 'set') {
		$('#set').show();
	}
	$('#method').change(function(){
		if($('#method').val() == 'set') {
			$('#set').show();
		} else {
			$('#set').hide();
		}
	});
});
</script>
<?php    echo t('This block will display up to 30 flickr photos perpage of the given account. Please know that there are some limitations in order to comply with the flickr api <a href="http://www.flickr.com/services/api/tos/" target="_blank">Terms of Use</a>.'); ?>
<hr />
<fieldset id="flickr_setup">
  <legend><?php        echo t('Flickr Setup'); ?></legend>
  <table width="100%">
  	<tr>
    	<td width="35%" valign="top"><?php    echo $form->label('flickr_email', 'Flickr Email:');?></td>
        <td width="65%" valign="top"><?php    echo $form->text('flickr_email', $flickr_email, array('style' => 'width: 200px'));?></td>
    </tr>
 </table>
</fieldset>
<br />
<fieldset id="general_settings">
  <legend><?php        echo t('General Settings'); ?></legend>
  <table width="100%">
  	<tr>
    	<td width="35%" valign="top"><?php    echo $form->label('method', 'Display Type:'); ?></td>
        <td width="65%" valign="top"><?php    echo $form->select('method', array('photostream' => 'Photostream', 'set' => 'Set'), $method); ?></td>
    </tr>
    <tr id="set" style="display:none">
    	<td width="35%" valign="top"><?php    echo $form->label('photoset_id', 'Photoset Id:');?></td>
        <td width="65%" valign="top"><?php    echo $form->text('photoset_id', $photoset_id, array('style' => 'width: 200px'));?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('display_columns', 'Display Columns:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->text('display_columns', $display_columns, array('style' => 'width: 30px')); ?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('per_page', 'Photos Per Page:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->text('per_page', $per_page, array('style' => 'width: 30px')); ?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('thumbnail_size', 'Thumbnail Size:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->text('thumbnail_size', $thumbnail_size, array('style' => 'width: 30px')); ?><?php        echo t('px'); ?></td>
    </tr>
   </table>
</fieldset>
<br />
<fieldset id="lightbox_effects">
  <legend><?php        echo t('Lightbox Effects'); ?></legend>
  <table width="100%">
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('enable_lightbox', 'Enable:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->checkbox('enable_lightbox', 1, $enable_lightbox, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
    <tr>
    <td width="35%" valign="top"><?php    echo $form->label('image_size', 'Image Size:'); ?></td>
    <td width="65%" valign="top"><?php    echo $form->select('image_size', array('500' => '500px', '640' => '640px'), $image_size); ?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('lightbox_mouse_scroll', 'Mouse Scrolling:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->checkbox('lightbox_mouse_scroll', 1, $lightbox_mouse_scroll, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('lightbox_effect', 'Trasition Effect:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->select('lightbox_effect', array('fade' => 'Fade', 'elastic' => 'Elastic', 'none' => 'None'), $lightbox_effect); ?></td>
    </tr>
    <tr>
      <td width="35%" valign="top"><?php    echo $form->label('lightbox_title_position', 'Title Position:'); ?></td>
      <td width="65%" valign="top"><?php    echo $form->select('lightbox_title_position', array('outside' => 'Outside', 'inside' => 'Inside', 'over' => 'Over'), $lightbox_title_position); ?></td>
    </tr>
  </table>
</fieldset>
<br />
<fieldset id="pagination_settings">
	<legend><?php   echo t('Pagination Settings'); ?></legend>
    <table width="100%">
    <tr>
    	<td width="35%" valign="top"><?php    echo $form->label('display_pagination', 'Pagination:');?></td>
      	<td width="65%" valign="top"><?php    echo $form->checkbox('display_pagination', 1, $display_pagination, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
    <tr>
    	<td width="35%" valign="top"><?php    echo $form->label('display_page_numbers', 'Page Numbers:');?></td>
      	<td width="65%" valign="top"><?php    echo $form->checkbox('display_page_numbers', 1, $display_page_numbers, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
    <tr>
    	<td width="35%" valign="top"><?php    echo $form->label('display_prev_next', 'Previous/Next:');?></td>
      	<td width="65%" valign="top"><?php    echo $form->checkbox('display_prev_next', 1, $display_prev_next, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
    <tr>
    	<td width="35%" valign="top"><?php    echo $form->label('display_image_count', 'Image Count:');?></td>
      	<td width="65%" valign="top"><?php    echo $form->checkbox('display_image_count', 1, $display_image_count, array('style' => 'margin-right: 0;')); ?></td>
    </tr>
 </table>
</fieldset>
<?php        } ?>
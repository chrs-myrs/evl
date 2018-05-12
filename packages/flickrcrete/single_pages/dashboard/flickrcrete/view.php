<?php         
defined('C5_EXECUTE') or die(_("Access Denied.")); 
$form = Loader::helper('form');
$pkg = Package::getByHandle('flickrcrete');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t($title), t($description), false, false);?>

<div class="ccm-pane-body">
	<form id='settings-form' action="<?php         echo $this->action('save')?>" method="post">
		<fieldset id="flickr_setup">
		<legend><?php   echo t('Flickr Settings')?></legend>
			<p><?php   echo t('If you are in need of an api key you can request one at')?> <a href="http://www.flickr.com/services/apps/create/apply/"><?php        echo t('www.flickr.com'); ?></a></p>
			<table class="table table-bordered" border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td colspan=2>
				<label><?php        echo t('Please Enter Your Flickr Api Key.')?></label>
				</td>
			</tr>
			<tr>
				<td><?php        echo $form->label('api_key', 'Api Key:');?></td>
				<td><?php         $api_key = $pkg->config('FLICKRCRETE_API_KEY'); ?><?php        echo $form->text('FLICKRCRETE_API_KEY', $api_key, array('style' => 'width: 250px'));?></td>
			</tr>
			</table>
			<?php         
			$concrete_interface = Loader::helper('concrete/interface');	
			echo $concrete_interface->submit('Save','flickrcrete');?>
		</fieldset>
		</form>

</div>
<div class="ccm-pane-footer"></div>
<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);?>
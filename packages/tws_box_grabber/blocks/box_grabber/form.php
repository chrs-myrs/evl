<?php 
defined('C5_EXECUTE') or die("Access Denied.");
    $form = Loader::helper('form');
?>
<table class="tiger-striped">
    <tr>
        <td align="right">
            <?php  echo $form->label('title', t('Title:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('title', $title, array('style' => 'width:100px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('url', t('Source URL:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('url', $url, array('style' => 'width:350px')); ?></span>
        </td>
    </tr>
    <tr>
        <td align="right">
            <?php  echo $form->label('selector', t('CSS Selector:')); ?>
        </td>
	<td align="left">
            <span class="optionField"><?php  echo $form->text('selector', $selector, array('style' => 'width:200px')); ?></span>
        </td>
    </tr>    
</table>
<p style="margin-bottom:0;"><strong><?php echo t('Notes'); ?>:</strong></p>
<ul style="margin:0;">
<li><?php echo t('Source URL is simply the url of the page you with to pull data from.'); ?></li>
<li><?php echo t('CSS Selector is a jQuery style reference to the element you wish to extract. See phpquery documentation here'); ?> - <a href="http://code.google.com/p/phpquery/wiki/Selectors">http://code.google.com/p/phpquery/wiki/Selectors</a>.</li>
</ul>

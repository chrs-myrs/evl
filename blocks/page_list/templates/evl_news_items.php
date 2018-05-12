<?php
defined('C5_EXECUTE') or die("Access Denied.");
$rssUrl = $showRss ? $controller->getRssUrl($b) : '';
$textHelper = Loader::helper("text");
	$imgHelper = Loader::Helper('image');
//$ih = Loader::helper('image'); //<--uncomment this line if displaying image attributes (see below)
//Note that $nh (navigation helper) is already loaded for us by the controller (for legacy reasons)
?>

<div class="ccm-page-list">

	<?php foreach ($pages as $cobj):

		$target = $cobj->getAttribute('nav_target');

		$title = $cobj->getCollectionName();
		$date = $cobj->getCollectionDatePublic('M j, Y'); ?>

	<div class="grid_4 main-content-thumb content-block news-item">
	<h2><?php  echo $date . " &#151; " . $title?></h2>
	<hr>

	<?php  
		$ts = $cobj->getBlocks('Thumbnail Image');
		if (is_object($ts[0])) {
			$tsb = $ts[0]->getInstance();
			$thumb = $tsb->getFileObject();
			if(!$thumb->error){ ?>
				<div class="image-link" style="float: left;">
				<a <?php   if ($target != '') { ?> target="<?php  echo $target?>" <?php   } ?> href="<?php  echo $nh->getLinkToCollection($cobj)?>" style="background-image: url(<?php echo $thumb->getRelativePath(); ?>);">
				</a></div>
				<?php
			}
		}
	?>
	<p>
		<?php  
		if(!$controller->truncateSummaries){
			echo $cobj->getCollectionDescription();
		}else{
			echo $textHelper->wordSafeShortText($cobj->getCollectionDescription(),$controller->truncateChars);
		}
		?>
	</p>
	<p><a <?php   if ($target != '') { ?> target="<?php  echo $target?>" <?php   } ?> href="<?php  echo $nh->getLinkToCollection($cobj)?>">Read more...</a></p>
	</div>
	
	<?php endforeach; ?>
 

	<?php if ($showRss): ?>
		<div class="ccm-page-list-rss-icon">
			<a href="<?php echo $rssUrl ?>" target="_blank"><img src="<?php echo $rssIconSrc ?>" width="14" height="14" alt="<?php echo t('RSS Icon') ?>" title="<?php echo t('RSS Feed') ?>" /></a>
		</div>
		<link href="<?php echo BASE_URL.$rssUrl ?>" rel="alternate" type="application/rss+xml" title="<?php echo $rssTitle; ?>" />
	<?php endif; ?>
 
</div><!-- end .ccm-page-list -->


<?php if ($showPagination): ?>
	<div id="pagination">
		<div class="ccm-spacer"></div>
		<div class="ccm-pagination">
			<span class="ccm-page-left"><?php echo $paginator->getPrevious('&laquo; ' . t('Previous')) ?></span>
			<?php echo $paginator->getPages() ?>
			<span class="ccm-page-right"><?php echo $paginator->getNext(t('Next') . ' &raquo;') ?></span>
		</div>
	</div>
<?php endif; ?>

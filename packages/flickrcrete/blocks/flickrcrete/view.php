<?php       defined('C5_EXECUTE') or die(_("Access Denied.")); 
$bID = $controller->bID;
$rel = 'fancybox'.$bID;
$c = Page::getCurrentPage();
?>
<style>
#flickrcrete_<?php   echo $bID; ?> {width:100%; position:relative;}
#flickrcrete_<?php   echo $bID; ?> .image{width:<?php      echo $column_width; ?>; float: left; text-align:center; position:relative;}
#flickrcrete_<?php   echo $bID; ?> .image img{height:<?php      echo $thumbnail_size; ?>; width:<?php      echo $thumbnail_size; ?>; padding:3px; border:solid 1px #cccccc; margin:3px; background-color:#FFFFFF;}
#flickrcrete_<?php   echo $bID; ?> .linkback{position:absolute; width:<?php      echo $linkback_size; ?>; height:25px; bottom:8px; left:50%;}
#flickrcrete_<?php   echo $bID; ?> .linkback img{padding:0px; margin:0px; height:25px; width:25px; background-color:transparent; border:none;}

#flickrcrete_<?php   echo $bID; ?> .pages{text-align:center;margin-bottom:20px;margin-top:20px}
#flickrcrete_<?php   echo $bID; ?> .pagination{font-size:12px;line-height:22px;padding-top:6px;margin-left:auto;margin-right:auto}
#flickrcrete_<?php   echo $bID; ?> .pagination a,
#flickrcrete_<?php   echo $bID; ?> .pagination .current{padding:2px 6px;border:solid 1px #eee;background:#fff;text-decoration:none}
#flickrcrete_<?php   echo $bID; ?> .pagination a:visited{padding:2px 6px;border:solid 1px #eee;background:#fff;text-decoration:none}
#flickrcrete_<?php   echo $bID; ?> .pagination .at_start{margin-right:20px;padding:2px 6px;border:solid 1px #eee;background:#fff;color:#bbb}
#flickrcrete_<?php   echo $bID; ?> .pagination .previous{margin-right:20px; padding:2px 6px;border:solid 1px #eee;background:#fff}
#flickrcrete_<?php   echo $bID; ?> .pagination .break{padding:2px 6px;border:none;background:transparent;text-decoration:none;color:#bbb}
#flickrcrete_<?php   echo $bID; ?> .pagination .next{margin-left:20px; padding:2px 6px;border:solid 1px #eee;background:#fff}
#flickrcrete_<?php   echo $bID; ?> .pagination .at_end{margin-left:20px;padding:2px 6px;border:solid 1px #eee;background:#fff;color:#bbb}
#flickrcrete_<?php   echo $bID; ?> .pagination .current{padding:2px 6px;border:solid 1px #eee;font-weight:bold; background:#fff;color:#FF0084}
#flickrcrete_<?php   echo $bID; ?> .pagination a:hover{color:#fff;background:#0063DC;border-color:#fff;text-decoration:none}
#flickrcrete_<?php   echo $bID; ?> .pagination .results{text-align:center;font:11px/15px Arial,Helvetica;color:#bbb;margin-top:8px}
</style>

<div id="flickrcrete_<?php   echo $bID; ?>">
<?php       
//check for errors
if($error){echo $error;}
//display flickrcrete
else{
	foreach ($images as $img){
			$image = '<img src="'.$img['thumbnail_src'].'" alt="'.$title.'"/>';
			if($img['description'] != ''){$title= $img['title'].' : '.$img['description'];}
			else{$title = $img['title'];}
			echo '<div class="image">';
			if($enable_lightbox == 1){echo '<a rel="'.$rel.'" href="'.$img['full_image_src'].'" title="'.$title.'">'.$image.'</a>';}
			else{echo '<a rel="'.$rel.'" href="'.$img['flickr_link'].'" title="'.$title.'">'.$image.'</a>';}
			echo '<div class="linkback"><a href="'.$img['flickr_link'].'"><img src="'.$this->getBlockURL().'/images/link.png"/></a></div>		
			</div>';
	}
	if($pages > 1 && $display_pagination == 1){
		echo '<br style="clear:both"/>';
		echo '<div class="pages">';
		echo '<div class="pagination">';
		
		if($display_prev_next != 0){
			$previous = $page - 1;
			if($previous < 1){echo '<span class="at_start">&larr; '.t('Previous').'</span>';}
			else{echo '<a class="previous" href="'.$current_url.'fcp='.$previous.'">&larr; '.t('Previous').'</a>';}
		}
		
		if($display_page_numbers != 0){
			if($pages < 10){$start=1;$limit=$pages;}
			elseif($pages > 10 && $page - 5 <= 1){$start=1; $limit=10; $after=1;}
			elseif($pages > 10 && $page - 5 > 1 && $page + 5 <= $pages){$start=$page-4; $limit=$page+5; $after=1; $before=1;}
			elseif($pages > 10 && $page + 5 >= $pages){$start=$pages-9; $limit=$pages; $before=1;}
			
			if($before == '1'){echo '<span class="break">...</span>';}
			
			for ($i=$start; $i<=$limit; $i++){
			if($page == $i){echo '<span class="current">'.$i.'</span>';}
			else{echo '<a href="'.$current_url.'fcp='.$i.'">'.$i.'</a>';}
			}
			
			if($after == '1'){echo '<span class="break">...</span>';}
		}
		
		if($display_prev_next != 0){
			$next = $page + 1;
			if($next > $pages){echo '<span class="at_end">'.t('Next').' &rarr;</span>';}
			else{echo '<a class="next" href="'.$current_url.'fcp='.$next.'">'.t('Next').' &rarr;</a>';}
		}
		
		if($display_image_count != 0){echo '<div class="results">('.$total.' '.t('items').')</div>';}
		echo '</div>';
		echo '</div>';
	}
}
?>	

<script language="javascript">
$('#flickrcrete_<?php      echo $bID; ?> .linkback').hide();
$('#flickrcrete_<?php      echo $bID; ?> .image').hoverIntent(
  function () {
    $('.linkback', this).stop(true, true).show('slow');
  }, 
  function () {
    $('.linkback', this).stop(true, true).hide('slow');
  }
);
</script>

<?php       if (!$c->isEditMode() && $enable_lightbox == 1 && count($images) > 0): /* fancybox init chokes if no applicable dom elements */ ?>
<script type="text/javascript">
$(document).ready(function(){
	$('a[rel="<?php       echo $rel; ?>"]').fancybox({
		'transitionIn' : '<?php       echo $lightbox_effect; ?>',
		'transitionOut' : '<?php       echo $lightbox_effect; ?>',
		'titlePosition' : '<?php       echo $lightbox_title_position; ?>'
	});
});
</script>
<?php       endif; ?>

</div>
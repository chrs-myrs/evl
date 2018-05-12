<?php       defined('C5_EXECUTE') or die(_("Access Denied.")); 
$bID = $controller->bID;
$rel = 'fancybox'.$bID;
$c = Page::getCurrentPage();
?>
  <script type="text/javascript">
  $(function() {
    var galleries = $('.ad-gallery').adGallery({
	  loader_image: 'loader.gif',
	  width: 700, // Width of the image, set to false and it will read the CSS width
	  height: 400, // Height of the image, set to false and it will read the CSS height
	  thumb_opacity: 0.7, // Opacity that the thumbs fades to/from, (1 removes fade effect)
						  // Note that this effect combined with other effects might be resource intensive
						  // and make animations lag
	  start_at_index: 0, // Which image should be displayed at first? 0 is the first image
	  description_wrapper: false, // Either false or a jQuery object, if you want the image descriptions
											   // to be placed somewhere else than on top of the image
	  animate_first_image: false, // Should first image just be displayed, or animated in?
	  animation_speed: 400, // Which ever effect is used to switch images, how long should it take?
	  display_next_and_prev: true, // Can you navigate by clicking on the left/right on the image?
	  display_back_and_forward: true, // Are you allowed to scroll the thumb list?
	  scroll_jump: 0, // If 0, it jumps the width of the container
	  effect: 'slide-hori', // or 'slide-vert', 'resize', 'fade', 'none' or false
  	  enable_keyboard_move: true, // Move to next/previous image with keyboard arrows?
 	  cycle: true // If set to false, you can't go from the last image to the first, and vice versa
	});
  });
  </script>
<style>
#flickrcrete_<?php   echo $bID; ?> {width:100%; position:relative;}
#flickrcrete_<?php   echo $bID; ?> .image{width:<?php      echo $column_width; ?>; float: left; text-align:center; position:relative;}
#flickrcrete_<?php   echo $bID; ?> .image img{height:<?php      echo $thumbnail_size; ?>; width:<?php      echo $thumbnail_size; ?>; padding:3px; border:solid 1px #cccccc; margin:3px; background-color:#FFFFFF;}
#flickrcrete_<?php   echo $bID; ?> .linkback{position:absolute; width:<?php      echo $linkback_size; ?>; height:25px; bottom:8px; left:50%;}
#flickrcrete_<?php   echo $bID; ?> .linkback img{padding:0px; margin:0px; height:25px; width:25px; background-color:transparent; border:none;}
</style>

<div id="flickrcrete_<?php   echo $bID; ?>">
<div id="gallery" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-controls">
      </div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
<?php   
//check for errors
if($error){echo $error;}
//display flickrcrete
else{
	foreach ($images as $img){
			$image = '<img src="'.$img['thumbnail_src'].'" title="'.$img['title'].'" alt="'.$img['description'].'"/>';
			echo '<li>
				<a href="'.$img['full_image_src'].'">'.$image.'</a>
				</li>
				';
	}
}
?>	
		  </ul>
        </div>
      </div>
    </div>

    <div id="descriptions">
    </div>
    
</div>
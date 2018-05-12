<?php 
    defined('C5_EXECUTE') or die("Access Denied.");
    $this->inc('elements/header.php'); ?>


            <section id="body" class="no-sidebar">
                <div id="featured-image-full">
			<?php 
			
			if ($c->isEditMode()) {
				print '<br><br>';
				$a = new Area('Thumbnail Image');
				$a->display($c);
			}
			?>
		</div>
                <section id="content" class="content-block">
                    
                    <h1 class="page-title"><?PHP  echo strtoupper($c->getCollectionName()); ?></h1>
                    <hr />
                    <?PHP  
                    
			$a = new Area('Main');
			$a->display($c);
			?>
                    	<div id="main-content-post-author">
			
				<?php  
				$u = new User();
				if ($u->isRegistered()) { ?>
					<?php   
					if (Config::get("ENABLE_USER_PROFILES")) {
						$userName = '<a href="' . $this->url('/profile') . '">' . $u->getUserName() . '</a>';
					} else {
						$userName = $u->getUserName();
					}
				}
				?>

				<p><?php  echo t('Posted by: ');?><span class="post-author"><?php   echo $userName; ?> at <?php  echo $c->getCollectionDatePublic('g:i a')?> on <?php  echo $c->getCollectionDatePublic('F jS, Y')?></span>
				</p>	
			</div>
                    <div class="cleared"></div>
                </section>
                <section id="page-footer">
                    <?PHP  
			$a = new Area('Page_Footer');
			$a->display($c);
			?>
                </section>
            </section>


<?php $this->inc('elements/footer.php'); ?>
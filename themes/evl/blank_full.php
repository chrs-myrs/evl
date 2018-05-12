<?php 
    defined('C5_EXECUTE') or die("Access Denied.");
    $this->inc('elements/header.php'); ?>


            <section id="body">
                <section id="content">
                    <!-- <h1 class="page-title"><?PHP  echo strtoupper($c->getCollectionName()); ?></h1> -->
                    <?PHP  
                    
			$a = new Area('Main');
			$a->display($c);
			?>
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
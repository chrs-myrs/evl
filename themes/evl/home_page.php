<?php 
    defined('C5_EXECUTE') or die("Access Denied.");
    $this->inc('elements/header.php'); ?>


            <section id="body" class="home_page with-sidebar">
                 <section id="sidebar">
                 <?PHP  
                    $a = new Area('Sidebar');
                    $a->display($c);
                    ?>
                </section>

                <section id="content">   
                    <div id="marquee_holder">
                    <?PHP  
                    
			$a = new Area('Marquee');
			$a->display($c);
			?>
                    </div>
                    <div class="content-block">
                        <h1 class="page-title"><?PHP  echo strtoupper($c->getCollectionName()); ?></h1>
                        <hr />
                        <div class="home_page-image-holder">
                            <?PHP  

                            $a = new Area('Image');
                            $a->display($c);
                            ?>
                        </div>                    
                        <?PHP  

                            $a = new Area('Main');
                            $a->display($c);
                            ?>
                    </div>
                </section>
                <section id="page-footer" class="content-block">
                    <?PHP  
			$a = new Area('Page_Footer');
			$a->display($c);
			?>
                </section>
            </section>


<?php $this->inc('elements/footer.php'); ?>
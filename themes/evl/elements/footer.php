
            <footer>
                <section id="copyright">
                    <?PHP  
                        $a = new GlobalArea('Copyright');
                        $a->setBlockLimit(1);
                        $a->display($c);
                    ?>
                </section>
            </footer>
</section>
        <section id="post-footer">
            <?php 
                $u = new User();
                if ($u->isRegistered()) {
                $userName = $u->getUserName();
                ?>
            <span class="sign-in"><?php echo t('Currently logged in as <b>%s</b>.', $userName);?> <a href="<?php echo $this->url('/login', 'logout');?>"><?php echo t('Sign Out');?></a></span>
                <?php } else { ?>
            <span class="sign-in"><a href="<?php echo $this->url('/login', 'forward', $c->getCollectionID());?>" class="no-ajaxy"><?php echo t('Sign in');?></a></span>
            <?php  } ?>
            <span class="designers">Design by <a href="http://firepixel.co.uk" target="_blank" title="Quality Website Development based in London">FirePixel</a></span>
        </section>
      
        <?php
            Loader::element('footer_required'); ?>
    </body>
</html>
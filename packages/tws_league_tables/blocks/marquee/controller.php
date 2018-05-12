<?php
	class MarqueeBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "Marquee Block.";
		protected $btName = "Marquee";
		protected $btTable = 'btMarquee';
		protected $btInterfaceWidth = "350";
		protected $btInterfaceHeight = "300";
		
		public function on_page_view() {
                    if(strlen($this->content > 1)) {
   			$html = Loader::helper('html');
			$this->addHeaderItem($html->javascript('jquery.marquee.js'));
                    }
                }
                
	}
	
?>
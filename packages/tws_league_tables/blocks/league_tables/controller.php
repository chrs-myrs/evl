<?php
	defined('C5_EXECUTE') or die(_("Access Denied."));
        
	class LeagueTablesBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "League Tables.";
		protected $btName = "League Tables.";
		protected $btTable = 'twsLeagueTableBlocks';
		protected $btInterfaceWidth = "400";
		protected $btInterfaceHeight = "465";
		protected $btCacheBlockRecord = false;
		protected $btCacheBlockOutputForRegisteredUsers = false;
		protected $btCacheBlockOutputLifetime = 600;
        
                public function on_page_view() {
                    //$html = Loader::helper('html');
                    //$this->addHeaderItem($html->javascript('box_grabber.js','tws_box_grabber'));
                }
		
	}
	
?>
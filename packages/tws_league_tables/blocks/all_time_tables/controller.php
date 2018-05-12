<?php
	defined('C5_EXECUTE') or die(_("Access Denied."));
        
	class AllTimeTablesBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "All-time League Tables.";
		protected $btName = "All-time League Tables.";
		protected $btTable = 'twsAllTimeTableBlocks';
		protected $btInterfaceWidth = "400";
		protected $btInterfaceHeight = "465";
		protected $btCacheBlockRecord = false;
		protected $btCacheBlockOutputForRegisteredUsers = false;
		protected $btCacheBlockOutputLifetime = 600;
        
                public function on_page_view() {
                    $html = Loader::helper('html');
                    $this->addHeaderItem($html->javascript('jquery.tablesorter.min.js','tws_league_tables'));
                }
		
	}
	
?>
<?php
	defined('C5_EXECUTE') or die(_("Access Denied."));
        
	class BoxGrabberBlockController extends BlockController {
		
		protected $btTable = 'twsBoxGrabber';
		protected $btInterfaceWidth = "400";
		protected $btInterfaceHeight = "280";
		protected $btCacheBlockRecord = true;
		protected $btCacheBlockOutput = true;
		protected $btCacheBlockOutputForRegisteredUsers = true;
		protected $btCacheBlockOutputLifetime = 1800;
        
       public function getBlockTypeName() {
               return t("Box Grabber");
       }

       public function getBlockTypeDescription() {
               return t("Pull elements from another site using jQuery syntax.");
       }
		
	}
	
?>
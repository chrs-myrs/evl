<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class TwsBoxGrabberPackage extends Package {

	protected $pkgHandle = 'tws_box_grabber';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.2'; 
	
	public function getPackageName() {
		return t("Box Grabber"); 
	}	
	
	public function getPackageDescription() {
		return t("Pull elements from another site using jQuery syntax.");
	}
	
	public function install() {
            $pkg = parent::install();
                BlockType::installBlockTypeFromPackage('box_grabber', $pkg);
	}
        
        public function upgrade() {
              parent::upgrade();
        }
	
	public function uninstall() {
		parent::uninstall();
		$db = Loader::db();
		//$db->Execute('DROP TABLE twsNewsSlider, twsNewsItems');
	}	
}
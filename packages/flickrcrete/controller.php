<?php          

defined('C5_EXECUTE') or die(_("Access Denied."));

class FlickrcretePackage extends Package {

	protected $pkgHandle = 'flickrcrete';
	protected $appVersionRequired = '5.6.1.0';
	protected $pkgVersion = '1.1.0';
	
	public function getPackageName() {
		return t("Flickrcrete");
	}
	
	public function getPackageDescription() {
		return t("Flickrcrete allows users to make use of their flickr account and fancybox, giving a nice clean way to embed your flickr images.");
	}
	
	public function install() {
		$pkg = parent::install();
		// install block		
		BlockType::installBlockTypeFromPackage('flickrcrete', $pkg);
		
		Loader::model('single_page');
		
		// install pages
		$cp = SinglePage::add('/dashboard/flickrcrete/', $pkg);
		$cp->update(array('cName'=>t('Flickrcrete'), 'cDescription'=>t('Flickrcrete Settings')));	
	}

}
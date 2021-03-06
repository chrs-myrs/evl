<?php  

defined('C5_EXECUTE') or die(_("Access Denied."));

class ContentPlusPackage extends Package {

     protected $pkgHandle = 'content_plus';
     protected $appVersionRequired = '5.5';
     protected $pkgVersion = '0.9.0';

     public function getPackageDescription() {
          return t("Makes it easy to add images and pullquotes to content.");
     }

     public function getPackageName() {
          return t("Content+");
     }
    
     public function install() {
          $pkg = parent::install();
    
          // install block
          BlockType::installBlockTypeFromPackage('content_plus', $pkg);
     }
    
}

?>
<?php  
  defined('C5_EXECUTE') or die(_("Access Denied."));

  class TweetcretePackage extends Package {
    protected $pkgDescription = 'Add a twitter feed to your website.';
    protected $pkgName = "Tweetcrete";
    protected $pkgHandle = 'tweetcrete';

    protected $appVersionRequired = '5.6.0';
    protected $pkgVersion = '1.7';

    function install() {
      $pkg = parent::install();
      BlockType::installBlockTypeFromPackage('tweetcrete', $pkg);
    }

    function upgrade() {
      parent::upgrade();
      /* Refresh all blocks */
      Loader::model('block_types');
      $items = array(BlockType::getByHandle('tweetcrete'));
      foreach($items as $item) {
        $item->refresh();
      }
    }
	public function uninstall() {
		BlockType::getByHandle('tweetcrete')->controller->uninstall();
		parent::uninstall();
		//make sure the block table gets dropped
		$db = Loader::db();
		$db->Execute('DROP TABLE IF EXISTS btTweetcrete');
		$db->Execute('DROP TABLE IF EXISTS btTweetcreteUserOrHashSettings');
	}
  }
?>

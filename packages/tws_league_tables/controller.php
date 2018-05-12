<?php 

defined('C5_EXECUTE') or die(_("Access Denied."));

class TwsLeagueTablesPackage extends Package {

	protected $pkgHandle = 'tws_league_tables';
	protected $appVersionRequired = '5.3.0';
	protected $pkgVersion = '1.0.13'; 
	
	public function getPackageName() {
		return t("League Tables"); 
	}	
	
	public function getPackageDescription() {
		return t("Manage League Tables.");
	}
	
	public function install() {
            $pkg = parent::install();
                BlockType::installBlockTypeFromPackage('league_tables', $pkg);
                BlockType::installBlockTypeFromPackage('all_time_tables', $pkg);
                BlockType::installBlockTypeFromPackage('marquee', $pkg);

                Loader::model('single_page');
                $sp = SinglePage::add('dashboard/league_tables', $pkg);
                $sp->update(array('cName'=>t('League Tables'), 'cDescription'=>t('Manage League Tables.')));
                $sp2 = SinglePage::add('dashboard/league_tables/seasons', $pkg);
                $sp2->update(array('cName'=>t('Seasons'), 'cDescription'=>t('Manage Seasons')));
                $sp3 = SinglePage::add('dashboard/league_tables/divisions', $pkg);
                $sp3->update(array('cName'=>t('Divisions'), 'cDescription'=>t('Manage Divisions')));
                $sp4 = SinglePage::add('dashboard/league_tables/teams', $pkg);
                $sp4->update(array('cName'=>t('Teams'), 'cDescription'=>t('Manage Teams')));
	}
        
        public function upgrade() {
              parent::upgrade();
              BlockType::installBlockTypeFromPackage('marquee', $this);
        }
	
	public function uninstall() {
		parent::uninstall();
		$db = Loader::db();
                Page::getByPath('dashboard/league_tables')->delete();
		//$db->Execute('DROP TABLE twsNewsSlider, twsNewsItems');
	}	
}
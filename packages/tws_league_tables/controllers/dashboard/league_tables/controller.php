<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardLeagueTablesController extends Controller {
	protected $sendUndefinedTasksToView = false;
	public function on_start() {
		/*$subnav = array(
			array(View::url('/dashboard/settings'), t('General')),
			array(View::url('/dashboard/settings/mail'), t('Email'), true),
			array(View::url('/dashboard/settings', 'set_permissions'), t('Access')),
			array(View::url('/dashboard/settings', 'set_developer'), t('Debug')),
			array(View::url('/dashboard/settings', 'manage_attribute_types'), t('Attributes'))
		);
		$this->set('subnav', $subnav);*/
	}
        
        public function delete_record() {
            $db = Loader::db();
            $id = $db->escape($_POST['id']);
            $db->execute("DELETE FROM twsLeagueTables WHERE rID=?", array($id));
            echo "OK";
            exit;
        }

}

?>
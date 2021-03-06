<?php
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardLeagueTablesDivisionsController extends Controller {
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
        
        public function save_sort() {

            $db = Loader::db();
            $orderstring = $db->escape($_POST['order']);
            $order = explode(',', $orderstring);
            echo sizeof($order).'#'.$orderstring;
            for($i = 0 ; $i < count($order) ; $i++) {
                $db->execute("UPDATE twsDivisions SET sortorder=? WHERE dID=?", array($i+1, $order[$i]));
            }
            exit;
        }
        
        public function new_division() {
            $db = Loader::db();
            $name = $db->escape($_POST['name']);
            $db->execute("INSERT INTO twsDivisions SET name=?, sortOrder=0", array($name));
            exit;
        }
        
        public function delete_division() {
            $db = Loader::db();
            $id = $db->escape($_POST['id']);
            $check = $db->execute("SELECT rID FROM twsLeagueTables WHERE division=?", array($id));
            if($check->RecordCount()>0) {
                echo "Cannot delete, there are still records associated with this division.";
            } else {
                $db->execute("DELETE FROM twsDivisions WHERE dID=?", array($id));
                echo "OK";
            }
            exit;
        }

}

?>
<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardLeagueTablesSeasonsController extends Controller {
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
            $orderstring = mysql_real_escape_string($_POST['order']);
            $order = explode(',', $orderstring);
            echo sizeof($order).'#'.$orderstring;
            for($i = 0 ; $i < count($order) ; $i++) {
                $db->execute("UPDATE twsSeasons SET sortorder=? WHERE sID=?", array($i+1, $order[$i]));
            }
            exit;
        }
        
        public function new_season() {
            $db = Loader::db();
            $name = mysql_real_escape_string($_POST['name']);
            $db->execute("INSERT INTO twsSeasons SET name=?, sortorder=0", array($name));
            exit;
        }
        
        public function delete_season() {
            $db = Loader::db();
            $id = mysql_real_escape_string($_POST['id']);
            $check = $db->execute("SELECT rID FROM twsLeagueTables WHERE season=?", array($id));
            if($check->RecordCount()>0) {
                echo "Cannot delete, there are still records associated with this season.";
            } else {
                $db->execute("DELETE FROM twsSeasons WHERE sID=?", array($id));
                echo "OK";
            }
            exit;
        }

}

?>
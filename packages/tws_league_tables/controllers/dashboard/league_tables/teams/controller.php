<?php 
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardLeagueTablesTeamsController extends Controller {
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
                $db->execute("UPDATE twsTeams SET sortorder=? WHERE tID=?", array($i+1, $order[$i]));
            }
            exit;
        }
        
        public function new_team() {
            $db = Loader::db();
            $name = mysql_real_escape_string($_POST['name']);
            $db->execute("INSERT INTO twsTeams SET name=?, sortorder=0", array($name));
            exit;
        }
        
        public function delete_team() {
            $db = Loader::db();
            $id = mysql_real_escape_string($_POST['id']);
            $check = $db->execute("SELECT rID FROM twsLeagueTables WHERE team=?", array($id));
            if($check->RecordCount()>0) {
                echo "Cannot delete, there are still records associated with this team.";
            } else {
                $db->execute("DELETE FROM twsTeams WHERE tID=?", array($id));
                echo "OK";
            }
            exit;
        }
        
        public function edit_team() {
            $db = Loader::db();
            $tID = mysql_real_escape_string($_POST['tID']);
            $name = mysql_real_escape_string($_POST['name']);
            $sortteam = mysql_real_escape_string($_POST['sortteam']);
            $db->execute("UPDATE twsTeams SET name=?, groupID=? WHERE tID=?", array($name, $sortteam, $tID));
            var_dump($_POST);
        }

}

?>
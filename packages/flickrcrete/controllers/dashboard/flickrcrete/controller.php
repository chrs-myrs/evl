<?php          
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardFlickrcreteController extends Controller {
	
	public function view() {
	
	}

	public function save(){
		$pkg = Package::getByHandle('flickrcrete');
		$pkg->saveConfig('FLICKRCRETE_API_KEY', $this->post('FLICKRCRETE_API_KEY'));
		$this->redirect('/dashboard/flickrcrete','success');
	}
	
	public function success() {
		$this->set('message','API Key Saved Successfully');
		$this->view();
	}
}
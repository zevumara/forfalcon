<?php
class categoriesController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function install()
	{
		Session::restricAccess('administrator');

		$install = $this->loadModel('install');		
		$installed = $install->install($this->getPost('name'), $this->getPost('access'));

		if ($installed)
		{
			$this->redirect('index/');
		}
	}
	
	public function uninstall()
	{
		Session::restricAccess('administrator');

		$install = $this->loadModel('install');		
		$install->uninstall($this->getPostInt('id'));

		$this->redirect('index/');
	}
	
	public function list()
	{
		Session::restricAccess('administrator');
		
		$this->view->title = $this->view->__localization['Manage_categories'];
		
		$records = $this->loadModel('categories');

		$this->view->records = $records->getRecords();
		$this->view->recordsFounded = $records->countRecords();
		
		$this->view->addFlag('categories');
		$this->view->render('list');
	}
	
	public function new()
	{
		Session::restricAccess('administrator');
		
		$this->view->title = $this->view->__localization['New_category'];
		
		// Autocomplete		
		$this->view->p_name = '';		
		if ($this->getPost('name')) {
			$this->view->p_name = $this->getPost('name');
		}

		if ($this->getPostInt('save')) {
			$records = $this->loadModel('categories');			
			$id = $records->newRecord(
				$this->getPost('name'),
				$this->getPostInt('active')
			);
			
			//$this->redirect('module/gallery/categories/edit/'. $id . '/12/');
			$this->redirect('module/gallery/categories/');
		}
		
		$this->view->addFlag('new-category');
		$this->view->render('new');
	}
	
	public function edit($id, $success = false, $error = false)
	{
		Session::restricAccess('administrator');
		
		$this->view->title = $this->view->__localization['Edit_category'];
		
		$language = $this->getPost('language');
		$records = $this->loadModel('categories');
		
		if ($this->getPostInt('save')) {			
			$records->editRecord(
				$this->getInt($id),
				$this->getPost('name'),
				$this->getPostInt('active'),
				$this->getPost('language')
			);
			
			$this->view->_success = $this->view->__localization['MESSAGE'][12];
		}

		// Message from URL
		if ($success) {
			$this->view->_success = $this->view->__localization['MESSAGE'][$success];
		}
		
		// Get record
		$record = $records->getRecord($this->getInt($id), $language);
		
		// Autocomplete
		$this->_autocomplete_edit_record($record);		
		
		$this->view->addFlag('categories');
		$this->view->render('edit');
	}
	
	private function _autocomplete_edit_record($record)
	{
		// Autocomplete

		$this->view->p_id = $record['id'];
		
		$this->view->p_name = $record['name'];
		if ($this->getPostString('name')) {
			$this->view->p_name = $this->getPostString('name');
		}
		
		$this->view->p_language = $record['language'];		
		if ($this->getPost('language')) {
			$this->view->p_language = $this->getPost('language');
		}
		
		$this->view->p_active = $record['active'];
	}
	
	public function delete($id)
	{
		Session::restricAccess('administrator');
		
		if ($this->getInt($id)) {
			global $__lang;
			
			$records = $this->loadModel('categories');
			$record = $records->getRecord($id, $__lang);
			$records->deleteRecord($id);
			
			$this->redirect('module/gallery/categories/');
		}
	}
}
?>
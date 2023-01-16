<?php
class recordsController extends Controller
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
		
		$this->view->title = $this->view->__localization['MANAGE_RECORDS'];
		
		$records = $this->loadModel('records');

		$this->view->records = $records->getRecords();
		$this->view->recordsFounded = $records->countRecords();
		
		$this->view->addFlag('records');
		$this->view->render('list');
	}
	
	public function new()
	{
		Session::restricAccess('administrator');
		
		$this->view->title = $this->view->__localization['NEW_RECORD'];

		$categories = $this->loadModel('categories');
		$this->view->categories = $categories->getRecords();
		
		// Autocomplete
		$this->view->p_title = '';		
		if ($this->getPost('title')) {
			$this->view->p_title = $this->getPost('title');
		}
		$this->view->p_idCategory = '';
		if ($this->getPostInt('idCategory')) {
			$this->view->p_idCategory = $this->getPostInt('idCategory');
		}
		
		if ($this->getPostInt('save')) {
			$image = $this->uploadImage('image');
			$records = $this->loadModel('records');			
			$id = $records->newRecord(
				$this->getPost('title'),
				$this->getPostInt('idCategory'),
				$image,
				$this->getPostInt('active')
			);
			
			//$this->redirect('module/gallery/records/edit/'. $id . '/12/');
			$this->redirect('module/gallery/records/');
		}
		
		$this->view->addFlag('new-record');
		$this->view->render('new');
	}
	
	public function edit($id, $success = false, $error = false)
	{
		Session::restricAccess('administrator');
		
		$this->view->title = $this->view->__localization['EDIT_RECORD'];
		
		$language = $this->getPost('language');
		$records = $this->loadModel('records');

		$categories = $this->loadModel('categories');
		$this->view->categories = $categories->getRecords();
		
		if ($this->getPostInt('save')) {
			$image = $this->uploadImage('image');		
			if ($image && $this->getPost('image_file')) {
				$this->deleteFile($this->getPost('image_file'));
			}
			
			$records->editRecord(
				$this->getInt($id),
				$this->getPostInt('idCategory'),
				$this->getPost('title'),
				$image,
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
		
		$this->view->addFlag('records');
		$this->view->render('edit');
	}
	
	private function _autocomplete_edit_record($record)
	{
		// Autocomplete

		$this->view->p_id = $record['id'];
		$this->view->p_image = $record['image'];
		$this->view->p_title = $record['title'];
		$this->view->p_idCategory = $record['idCategory'];
		
		if ($this->getPostString('title')) {
			$this->view->p_title = $this->getPostString('title');
		}
		
		if ($this->getPostInt('idCategory')) {
			$this->view->p_idCategory = $this->getPostInt('idCategory');
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
			
			$records = $this->loadModel('records');
			$record = $records->getRecord($id, $__lang);
			$this->deleteFile($record['image']);
			$records->deleteRecord($id);
			
			$this->redirect('module/gallery/');
		}
	}
}
?>
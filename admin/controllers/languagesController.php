<?php
class languagesController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		Session::restricAccess('supra');

		$this->view->title = $this->view->__localization['LANGUAGES'];
		$this->view->setJavascript(array('positioning'));

		$modules = $this->loadModel('modules');
		$this->view->modules = $modules->getModules();
		$this->view->render('index', 'languages');
	}
}
?>
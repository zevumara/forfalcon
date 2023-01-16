<?php
class installController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function install()
	{
		$install = $this->loadModel('install');		
		$installed = $install->install($this->getPost('name'), $this->getPost('access'));

		if ($installed)	{
			$this->redirect();
		}
	}
	
	public function uninstall()
	{
		$install = $this->loadModel('install');		
		$install->uninstall($this->getPostInt('id'));

		$this->redirect();
	}
}
?>
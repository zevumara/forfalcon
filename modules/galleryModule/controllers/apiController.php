<?php
class apiController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function list()
	{
		$records = $this->loadModel('api');
		$this->view->json('list');
	}
}
?>
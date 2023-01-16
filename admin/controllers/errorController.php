<?php
class errorController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index($index = 404)
	{
		$this->view->title = "Error";
		$this->view->error = $this->view->__localization['MESSAGE'][$index];
		$this->view->render('index', false, true);
	}
}
?>
<?php
class errorController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{		
		$this->view->title = "Error";
		$this->view->error = "error";
		$this->view->render('index', 'home');
	}
}
?>
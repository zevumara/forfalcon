<?php
class indexController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{		
		$this->view->title = "Home";
		$this->view->flags = array("item" => true, "hola" => true);
		$this->view->render('index', 'home');
	}
	
	public function suscribe()
	{
		$records = $this->loadModel('records');

			$email = $this->getPost('email');

			if ($this->validateEmail($email))
			{
				$records->newRecord(
					$this->getPostString('nickname'),
					$email,
					0
				);
			}
	}
}
?>
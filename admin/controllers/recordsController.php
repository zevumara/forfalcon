<?php
class recordsController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function list()
	{		
		$modules = $this->loadModel('modules');

		// Installed modules list
		$this->view->modules = $modules->getModules();		
		$modulesInstalled = array();		
		if ($this->view->modules)
		{
			foreach ($this->view->modules as $module)
			{
				$modulesInstalled[$module['name']] = 0;
			}
		}		

		// Creates the list for not installed modules		
		$this->view->modulesNotInstalled = array();		
		$modulesPath = opendir(ROOT_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);		
		while ($folder = readdir($modulesPath))
		{
			if (!is_dir($folder))
			{
				$moduleName = str_replace('Module', '', $folder);
				
				// Checks if is installed...				
				if (!array_key_exists($moduleName, $modulesInstalled))
				{
					// If not, adds to the list			
					$this->view->modulesNotInstalled[] = $moduleName;
				}
			}
		}

		if ($this->getPostInt('save_initial_module'))
		{
			$initial_module = $this->getPostString('initial_module');
			
			if ($this->getPostString('initial_module') != 'false')
			{
				$initial_module = "'" . $this->getPostString('initial_module') . "'";
			}
			
			$this->redirect();
		}

		$this->view->title = $this->view->__localization['CONFIGURATION'];
		$this->view->render('index');
	}
}
?>
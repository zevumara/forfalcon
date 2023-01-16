<?php
/**
 * class Controller
 * The abstract class for the controllers. Inherits the utility functions and compose the view.
 */
abstract class Controller extends Functions
{
	private $_singleton;
	
	protected $view;
	protected $request;
	protected $modules = array();
	protected $menu = array();
	
	public function __construct()
	{
		$this->_singleton = Singleton::get();
		$this->request = $this->_singleton->request;

		// Modules
		if (USE_MODULES === true) $this->modules = $this->loadModel('modules', APPLICATION_PATH)->getModules();
		// Dynamic menu
		if (DYNAMIC_MENU === true) $this->menu = $this->loadModel('menu', APPLICATION_PATH)->build();

		$this->view = new View($this->request, $this->modules, $this->menu);
	}

	/**
	 * Load a model from 'models' folder application or from a module.
	 * 
	 * @param	string		$model Model name
	 * @param	string		$path Use a custom path
	 * @access	protected
	 */
	protected function loadModel($model_name, $path = false)
	{
		$model = $model_name . 'Model';

		if ($path !== false) {
			// Custom path
			$model_file = $path . 'models' . DIRECTORY_SEPARATOR . $model . '.php';
		} else {
			// Working path
			$model_file = $this->request->getWorkingPath() . 'models' . DIRECTORY_SEPARATOR . $model . '.php';
		}
		
		// If model file exists...
		if (is_readable($model_file)) {
			// Includes the model file and returns an instance
			require_once $model_file;
			$model = new $model;
			return $model;
		} else {
			throw new Exception("Model file '$model_file' not found.");
		}
	}
}
?>
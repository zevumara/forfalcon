<?php
/**
 * class Request
 * Processes URL and manipulates it to get controller, method and arguments.
 */
class Request
{
	private $_controller;
	private $_method;
	private $_arguments;
	private $_working_path;
	private $_url;
	
	public function __construct()
	{
		// Default
		$this->_controller = DEFAULT_CONTROLLER;
		$this->_method = DEFAULT_METHOD;
		$this->_arguments = array();
		$this->_working_path = APPLICATION_PATH;

		// If the application requires login...
		if (REQUIRES_LOGIN === true && !Session::get('authenticated')) {
			// Login controller
			$this->_controller = 'login';
			// Ignore request
			return;
		}

		// Default external controller (module)
		if (USE_MODULES === true && DEFAULT_MODULE !== false) {
			$this->_controller = DEFAULT_MODULE;
			$this->_working_path = MODULES_PATH . DEFAULT_MODULE . 'Module' .  DIRECTORY_SEPARATOR;
		}

		// Data from URL
		if (isset($_GET['url']))
		{
			$url = $this->_transformUri(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL));
			$url = explode('/', $url);
			$url = array_filter($url); // Deletes empty parameters
			
			$controller = array_shift($url);

			// External controller (module)
			if (USE_MODULES === true && $controller === MODULE_URL_KEYWORD) {
				$module_name = array_shift($url);
				$controller = array_shift($url);
				$this->_working_path = MODULES_PATH . $module_name . 'Module' . DIRECTORY_SEPARATOR;
			}

			$method = array_shift($url);
			$this->_arguments = $url;
            
            // Redefine variables
            if ($controller) {
                $this->_controller = $controller;
            }
            if ($method) {
                $this->_method = $method;
            }
			
			$this->_url = URL . $this->_controller . '/' . $this->_method . '/';
		}
	}
	
	/**
	 * Supports hyphen in the url.
	 * 
	 * @param	string		$url
	 * @return	string		Url with hyphen.
	 */
	private function _transformUri($url)
	{
		$url = strtolower($url);
		$url = str_replace("-", "_", $url);
		
		return $url;
	}
	
	/**
	 * Returns working path.
	 * 
	 * @return	string		Workding directory
	 */
	public function getWorkingPath()
	{
		return $this->_working_path;
	}
	
	/**
	 * Returns controller name.
	 * 
	 * @return	string		Controller name
	 */
	public function getController()
	{
		return $this->_controller;
	}
	
	/**
	 * Returns method name.
	 * 
	 * @return	string		Method name
	 */
	public function getMethod()
	{
		return $this->_method;
	}
	
	/**
	 * Returns arguments.
	 * 
	 * @return	array		Arguments
	 */
	public function getArguments()
	{
		return $this->_arguments;
	}
	
	/**
	 * Returns current URL.
	 * 
	 * @return	string		Current url (with method)
	 */
	public function getURL()
	{
		return $this->_url;
	}
}
?>
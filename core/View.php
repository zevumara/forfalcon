<?php
 /**
 * class View
 * Builds the layout.
 */
class View
{
	public $__localization;

	private $_cssPath;
	private $_jsPath;
	private $_imgPath;
	private $_headerFile;
	private $_footerFile;
	private $_js;
	private $_routes;
	private $_modules;
	private $_menu;
	private $_flags;
	
	/**
	 * Get template parameters, localization files and save the routes for the view and javascripts folder. 
	 */
	public function __construct(Request $request, Array $modules, Array $menu)
	{
		// Request
		$this->_request = $request;

		// Get template parameters
		$this->_libPath = ROOT_URL . 'libraries/';
		$this->_cssPath = APPLICATION_URL . 'views/template/css/';
		$this->_jsPath = APPLICATION_URL . 'views/template/js/';
		$this->_imgPath = APPLICATION_URL . 'views/template/img/';
		$this->_headerFile = APPLICATION_PATH . 'views' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'header.php';
		$this->_footerFile = APPLICATION_PATH . 'views' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'footer.php';

		// Installed modules list
		$this->_modules = $modules;
		
		// Localization file
		$this->_getLocalizationFile();

		// Dynamic menu
		$this->_menu = $menu;
		
		// Routes for views and js folder
		$this->_routes = array();

		// Get controller
		$controller = $request->getController();
		
		// Routes
		$this->_routes['view'] = $request->getWorkingPath() . 'views' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR;
		$this->_routes['js'] = APPLICATION_URL . 'views/' . $controller . '/js/';
		
		// Flags
		$this->_flags = array();
		
		// View's URL
		$this->url = URL . $request->getController() . '/';
	}
	
	/**
	 * Get localization file.
	 */
	private function _getLocalizationFile()
	{
		global $__lang;
		
		$this->__localization = array();
		
		// Main localization file
		if (isset($__lang)) {
			$localization_file = ROOT_PATH . 'data' . DIRECTORY_SEPARATOR . $__lang . '.php';
			
			// Get localization file
			if (is_readable($localization_file)) {
				include_once $localization_file;
				
				// Saves the localization array for the view
				$this->__localization = $localization;
			}
		}
		
		// Localization files of installed modules
		foreach ($this->_modules as $module) {
			$moduleName = $module['name'] . 'Module';
			$localization_file = ROOT_PATH . 'modules' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $__lang . '.php';
					
			// Opens localization file of the module
			if (is_readable($localization_file))
			{
				include_once $localization_file;
				// Updates the localization array
				$newLocalization = array_merge($this->__localization, $localization);
				$this->__localization = $newLocalization;
			}
		}
	}
	
	/**
	 * Builds the view.
	 * 
	 * @param	string		$view View filename (without the extension .phtml)
	 * @param	string		$item Current page.
	 * @param	bool		$noLayout If it's true, it doesn't load the header and footer.
	 */
	public function render($view, $noLayout = false)
	{
		$view_file = $this->_routes['view'] . $view . '.phtml';
		
		if (is_readable($view_file)) {
			if ($noLayout === true) {
				include_once $view_file;
			} else {
				include_once $this->getHeaderFile();
				include_once $view_file;
				include_once $this->getFooterFile();
			}
		} else {
			throw new Exception("View file '$view_file' not found.");
		}
	}
	
	/**
	 * Returns the dynamic menu.
	 * @return	array		Menu.
	 */
	public function getMenu()
	{
		return $this->_menu;
	}

	public function inputHidden($key, $value = false)
	{
		if (!$value) {
			$value_key = 'p_' . $key;
			$value = $this->$value_key;
		}
		return "<input type='hidden' name='$key' value='$value'>";
	}

	public function inputText($key, $text, $required = '')
	{
		$value_key = 'p_' . $key;
		$value = $this->$value_key;
		echo "
		<div class='pb-3'>
			<label for='". $text ."InputText' class='form-label'>$text</label>
			<input type='text' name='$key' class='form-control' id='". $text ."InputText' value='$value' $required>
		</div>";
	}

	public function inputEmail($key, $text, $required = '')
	{
		$value_key = 'p_' . $key;
		$value = $this->$value_key;
		echo "
		<div class='pb-3'>
			<label for='". $text ."InputEmail' class='form-label'>$text</label>
			<input type='email' name='$key' class='form-control' id='". $text ."InputEmail' value='$value' $required>
		</div>";
	}

	public function inputPassword($key, $text, $description = false, $required = '')
	{
        $form_text = "";
        if ($description) {
            $form_text = "<div class='form-text'>$description</div>";
        }
		echo "
		<div class='pb-3'>
			<label for='". $text ."InputPassword' class='form-label'>$text</label>
			<input type='password' name='$key' class='form-control' id='". $text ."InputPassword' $required>
            $form_text
		</div>";
	}

	public function inputSelect($key, $text, $options, $required = '')
	{
		$value_key = 'p_' . $key;
		$value = $this->$value_key;
		$list = '';
		foreach ($options as $option){
			$selected = '';
			if ($value == $option['id']){
				$selected = 'selected';
			}
			$list .= "<option value='" . $option['id']. "' $selected>" . $option['name'] . "</option>";
		}
		echo "
		<div class='pb-3'>
			<label for='". $text ."InputSelect' class='form-label'>$text</label>
			<select name='$key' class='form-select' id='". $text ."InputSelect' $required>
				$list
			</select>
		</div>";
	}

	public function inputFileImage($key, $text, $description, $required = '')
	{
		$value_key = 'p_' . $key;
		$image = '';
		if (isset($this->$value_key)){
			$value = $this->$value_key;
			$image = "
            <div class='pb-3'>
                <a target='_blank' href='". PUBLIC_URL  . "uploads/images/$value'>
                    <img src='" . APPLICATION_URL . "uploads/thumbnails/$value' class='img-thumbnail'>
                </a>
            </div>";
		}
		echo "
		<div class='pb-3'>
			<label for='". $key . "InputFileImage' class='form-label'>
				$text
			</label>
            $image
			<input type='file' name='$key' class='form-control' id='". $key . "InputFileImage' $required>
		</div>";
	}

	public function showMessages()
	{
		if (isset($this->_success)) {
			echo "<div class='alert alert-success' role='alert'>$this->_success</div>";
		}
		if (isset($this->_error)) {
			echo "<div class='alert alert-danger' role='alert'>$this->_error</div>";
		}
	}
	
	/**
	 * Load javascript files.
	 * 
	 * @param	array		$jsList Javascript filename (without the extension .js)
	 * @param	bool		$publicFolder Load a javascript from the public folder.
	 */
	public function loadJs(array $jsList, $publicFolder = false)
	{
		if (!$publicFolder) {
			$urlJs = $this->_routes['js'];
		} else {
			$urlJs = PUBLIC_URL . 'js/';
		}
		
		if (is_array($jsList) && count($jsList)) {
			foreach ($jsList as $js) {
				$this->_js[] = $urlJs . $js . '.js';
			}
		} else {
			throw new Exception("Failed to load javascript files.");
		}
	}
	
	/**
	 * List of javascript files for the view.
	 * 
	 * @return	array string	Returns list of javascripts to load.
	 */ 
	protected function getJSs()
	{
		if (is_array($this->_js) && count($this->_js)) {
			return $this->_js;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns css path.
	 * @return	string		Css path.
	 */
	public function getCssPath()
	{
		return $this->_cssPath;
	}
	
	/**
	 * Returns libraries path.
	 * @return	string		Libraries path.
	 */
	public function getLibPath()
	{
		return $this->_libPath;
	}

	/**
	 * Returns javascript path.
	 * @return	string		Javascript path.
	 */
	public function getJsPath()
	{
		return $this->_jsPath;
	}

	/**
	 * Returns images path.
	 * @return	string		Images path.
	 */
	public function getImgPath()
	{
		return $this->_imgPath;
	}

	/**
	 * Returns header file.
	 * @return	string		Header file.
	 */
	public function getHeaderFile()
	{
		return $this->_headerFile;
	}

	/**
	 * Returns footer file.
	 * @return	string		Footer file.
	 */
	public function getFooterFile()
	{
		return $this->_footerFile;
	}

	/**
	 * 
	 * @param	array string	
	 * @return	bool		
	 */
	public function isFlagged($flag)
	{
        if (isset($this->_flags[$flag])) {
            return true;
        } else {
            return false;
        }
	}

	/**
	 * 
	 * @param	array string	
	 */
	public function addFlag($flag)
	{
		$this->_flags[$flag] = true;
	}
}
?>
<?php
/**
 * class Bootstrap
 * Processes the request and makes the call to the controller with its method and arguments.
 */
class Bootstrap
{
	public static function run(Request $request)
	{
		$controller = $request->getController() . 'Controller';
		$method = $request->getMethod();
		$arguments = $request->getArguments();
		$working_path = $request->getWorkingPath();

        $controller_file = $working_path . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php';
		
		// If called controller is valid...
		if (is_readable($controller_file)) {
			// Includes the controller file and creates an instance
			require_once $controller_file;
			$controller = new $controller;
			
			// If the method is not valid, calls to the default method
			if (!is_callable(array($controller, $method))) {
				$method = DEFAULT_METHOD;
			}
			
			// Important: This "call" to controller class and passes the method and its arguments
			call_user_func_array(array($controller, $method), $arguments);
		} else {
			// Controller not found
			throw new Exception("Controller file '$controller_file' not found.");
		}
	}
}
?>
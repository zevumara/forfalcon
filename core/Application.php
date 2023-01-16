<?php
/**
 * class Application
 * Main class.
 */
class Application
{
	public static function run()
	{
		try {
			// Session manager
			Session::init();

			// Creates and save the shared instances
			$singleton = Singleton::get();
			$singleton->request = new Request;

			try {
				// Database connection
                if (USE_DATABASE === true) {
                    $singleton->db = new Database;
                }
                // Run the application
                Bootstrap::run($singleton->request);
			}
			catch (PDOException $exception) {
				echo $exception->getMessage();
			}
		}
		catch (Exception $exception) {
			echo $exception->getMessage();
		}
	}
}
?>
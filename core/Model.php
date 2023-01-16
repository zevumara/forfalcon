<?php
/**
 * class Model
 * The abstract class for the models. Contains the database connection.
 */
abstract class Model
{
	private $_singleton;
	
	protected $db;
	
	// Get database connection
	public function __construct()
	{
		$this->_singleton = Singleton::get();
		$this->db = $this->_singleton->db;
	}
}
?>
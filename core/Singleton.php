<?php
/**
 * class Singleton
 * Singleton pattern.
 */
class Singleton
{
	private static $_instance;
	private $_objects;
	private function __construct(){}
	
	/**
	 * Gets its own instance.
	 * 
	 * @return	object		Self instace
	 * @access	public
	 */
	public static function get()
	{
		if (!self::$_instance instanceof self)
		{
			self::$_instance = new Singleton;
		}
		
		return self::$_instance;
	}
	
	/**
	 * Method for save an object.
	 */
	public function __set($name, $value)
	{
		$this->_objects[$name] = $value;
	}
	
	/**
	 * Method for get an object.
	 */
	public function __get($name)
	{
		if (isset($this->_objects[$name]))
		{
			return $this->_objects[$name];
		}
		
		return false;
	}
}
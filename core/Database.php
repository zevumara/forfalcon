<?php
/**
 * class Database
 * Main class for database connection.
 */
class Database extends PDO
{
	public function __construct($dbhost = DB_HOST, $dbname = DB_NAME, $dbuser = DB_USER, $dbpassword = DB_PASSWORD, $dbcharset = DB_CHARSET)
	{
		parent::__construct('mysql:host=' . $dbhost . ';dbname=' . $dbname, $dbuser, $dbpassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $dbcharset));
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	}
}
?>
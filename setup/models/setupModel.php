<?php
class setupModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function install($username, $password, $name, $email, $language, $prefix)
	{	
		$this->db->query("
		
		CREATE TABLE " . $prefix . "users(
			id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(50) NOT NULL,
			username VARCHAR(30) NOT NULL,
			password VARCHAR(60) NOT NULL,
			email VARCHAR(80) NOT NULL,
			avatar VARCHAR(100) NOT NULL,
			access ENUM('administrator', 'moderator', 'user'),
			active TINYINT DEFAULT '0'
		);
		
		");
		
		$this->db->query("
		
		CREATE TABLE " . $prefix . "languages(
			language varchar(5) NOT NULL PRIMARY KEY,
			name varchar(20) NOT NULL,
			active TINYINT NOT NULL,
			position INT(4) UNSIGNED
		);
		
		");
		
		$this->db->query("
		
		CREATE TABLE " . $prefix . "modules(
			id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(50) NOT NULL,
			access ENUM('administrator', 'moderator', 'user'),
			position INT(4) UNSIGNED
		);
		
		");
		
		$this->db->prepare("INSERT INTO " . $prefix . "users VALUES (null, :name, :username, :password, :email, '', 'administrator', 1)")
		->execute(
			array(
				':name' => $name,
				':username' => $username,
				':password' => $password,
				':email' => $email
			)
		);
		
		$this->db->prepare("INSERT INTO " . $prefix . "languages VALUES (:tag, :name, 1, 1)")
		->execute(
			array(
				':tag' => $language[0],
				':name' => $language[1]
			)
		);
	}
	
	public function isInstalled()
	{
		try
		{
			$administrator = $this->db->query("SELECT id FROM " . DB_PREFIX . "users WHERE access = 'administrator' LIMIT 1");
			$language = $this->db->query("SELECT language FROM " . DB_PREFIX . "languages LIMIT 1");
			if ($administrator && $language) {
				return true;
			} else {
				return false;
			}
		}
		catch(PDOException $exception)
		{
			return false;
		}	
	}
}
?>
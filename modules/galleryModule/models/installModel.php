<?php
class installModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function install($name)
	{
		$this->db->query("
		
			CREATE TABLE " . DB_PREFIX . "gallery(
				id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				idCategory INT(4) UNSIGNED,
				image VARCHAR(100) NOT NULL,
				active TINYINT DEFAULT '1',
				position INT(4) UNSIGNED
			);
		
		");
		
		$this->db->query("
		
			CREATE TABLE " . DB_PREFIX . "gallery_languages(
				idPhoto INT(4) UNSIGNED NOT NULL,
				language varchar(5) NOT NULL,
				title varchar(100) NOT NULL,
				KEY (idPhoto),
				KEY language (language)
			);
		
		");

		$this->db->query("
		
			CREATE TABLE " . DB_PREFIX . "gallery_categories(
				id INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				active TINYINT DEFAULT '1',
				position INT(4) UNSIGNED
			);
		
		");

		$this->db->query("
		
			CREATE TABLE " . DB_PREFIX . "gallery_categories_languages(
				idCategory INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				language varchar(5) NOT NULL,
				name VARCHAR(100) NOT NULL,
				KEY (idCategory),
				KEY language (language)
			);
		
		");

		$this->db->prepare("INSERT INTO " . DB_PREFIX . "modules VALUES (null, :name, :access, null)")
		->execute(
			array(
				':name' => $name,
				':access' => 'administrator'
			)
		);
		
		$this->db->query("UPDATE " . DB_PREFIX . "modules SET position = LAST_INSERT_ID() WHERE id = LAST_INSERT_ID()");
		
		return true;
	}
	
	public function uninstall($id)
	{		
		$this->db->query("DELETE FROM " . DB_PREFIX . "modules WHERE id = $id");
		$this->db->query("DROP TABLE " . DB_PREFIX . "gallery");
		$this->db->query("DROP TABLE " . DB_PREFIX . "gallery_languages");
		$this->db->query("DROP TABLE " . DB_PREFIX . "gallery_categories");
		$this->db->query("DROP TABLE " . DB_PREFIX . "gallery_categories_languages");

		return true;
	}
}
?>
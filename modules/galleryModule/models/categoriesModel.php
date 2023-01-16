<?php
class categoriesModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function countRecords()
	{
		global $__lang;
		
		$count = $this->db->prepare("SELECT
				count(*)
				FROM
				" . DB_PREFIX . "gallery_categories record,
				" . DB_PREFIX . "gallery_categories_languages language
				WHERE
				record.id = language.idCategory AND
				language.language = '$__lang'");
		$count->execute();

		return $count->fetchColumn();
	}
	
	public function getRecords()
	{
		global $__lang;

		$records = $this->db->query("SELECT
				record.id AS id,
				record.active AS active,
				language.name AS name
				FROM
				" . DB_PREFIX . "gallery_categories record,
				" . DB_PREFIX . "gallery_categories_languages language
				WHERE
				record.id = language.idCategory AND
				language.language = '$__lang'
				ORDER BY
				record.position DESC");
		
		return $records->fetchAll();
	}
	
	public function newRecord($name, $active)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_categories (id, active) VALUES (null, '$active')");
		$id = $this->db->lastInsertId();

		$this->db->query("UPDATE " . DB_PREFIX . "gallery_categories SET position = LAST_INSERT_ID() WHERE id = LAST_INSERT_ID()");
		
		global $__lang;
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_categories_languages (idCategory, language, name) VALUES ('$id', '$__lang', '$name')");
		
		return $id;
	}
	
	public function getRecord($id, $language = false)
	{
		global $__lang;
		
		if (!$language) {
			$language = $__lang;
		}
		
		$id = (int) $id;
		$record = $this->db->query("SELECT
				record.id AS id,
				record.active AS active,
				language.name AS name,
				language.language AS language
				FROM
				" . DB_PREFIX . "gallery_categories record,
				" . DB_PREFIX . "gallery_categories_languages language
				WHERE
				record.id = $id AND
				language.idCategory = $id AND
				language.language = '$language'
				LIMIT 1");
		
		$result = $record->fetch();
		
		if (!$result) {
			$original = $this->db->query("SELECT
				record.id AS id,
				language.name AS name
				FROM
				" . DB_PREFIX . "gallery_categories record,
				" . DB_PREFIX . "gallery_categories_languages language
					WHERE
					record.id = $id AND
					language.idCategory = $id AND
					language.language = '$__lang'
					LIMIT 1");
			
			$temp = $original->fetch();
			$original->closeCursor();
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_categories_languages (idCategory, language, name) VALUES ('" . $temp['id'] . "', '$language', '" . $temp['name'] . "')");

			$record = $this->db->query("SELECT
				record.id AS id,
				record.active AS active,
				language.name AS name,
				language.language AS language
				FROM
				" . DB_PREFIX . "gallery_categories record,
				" . DB_PREFIX . "gallery_categories_languages language
				WHERE
				record.id = $id AND
				language.idCategory = $id AND
				language.language = '$language'
				LIMIT 1");
			
			$result = $record->fetch();
		}
		
		return $result;
	}
	
	public function editRecord($id, $name, $active, $language)
	{
		$id = (int) $id;
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_categories SET active = '$active' WHERE id = $id");
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_categories_languages SET name = '$name' WHERE idCategory = $id AND language = '$language'");
	}
	
	public function deleteRecord($id)
	{
		$id = (int) $id;

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_categories WHERE id = $id");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_categories_languages WHERE idCategory = $id");
	}
}
?>
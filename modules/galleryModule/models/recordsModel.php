<?php
class recordsModel extends Model
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
				" . DB_PREFIX . "gallery record,
				" . DB_PREFIX . "gallery_languages language
				WHERE
				record.id = language.idPhoto AND
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
				record.idCategory AS idCategory,
				record.image AS image,
				record.position AS position,
				language.title AS title,
				category.name AS category
				FROM
				" . DB_PREFIX . "gallery record,
				" . DB_PREFIX . "gallery_languages language,
				" . DB_PREFIX . "gallery_categories_languages category
				WHERE
				record.id = language.idPhoto AND
				language.language = '$__lang' AND
				category.idCategory = record.idCategory
				ORDER BY
				record.position DESC");
		
		return $records->fetchAll();
	}
	
	public function newRecord($title, $idCategory, $image, $active)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery (id, idCategory, image, active) VALUES (null, '$idCategory', '$image', '$active')");		
		$id = $this->db->lastInsertId();
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery SET position = LAST_INSERT_ID() WHERE id = LAST_INSERT_ID()");
		
		global $__lang;		
		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_languages (idPhoto, language, title) VALUES ('$id', '$__lang', '$title')");
		
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
				record.idCategory AS idCategory,
				record.image AS image,
				language.title AS title,
				language.language AS language
				FROM
				" . DB_PREFIX . "gallery record,
				" . DB_PREFIX . "gallery_languages language
				WHERE
				record.id = $id AND
				language.idPhoto = $id AND
				language.language = '$language'
				LIMIT 1");
		
		$result = $record->fetch();
		
		if (!$result) {
			$original = $this->db->query("SELECT
				record.id AS id,
				record.idCategory AS idCategory,
				language.title AS title
				FROM
				" . DB_PREFIX . "gallery record,
				" . DB_PREFIX . "gallery_languages language
					WHERE
					record.id = $id AND
					language.idPhoto = $id AND
					language.language = '$__lang'
					LIMIT 1");
			
			$temp = $original->fetch();
			$original->closeCursor();
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_languages (idPhoto, language, title) VALUES ('" . $temp['id'] . "', '$language', '" . $temp['title'] . "')");

			$record = $this->db->query("SELECT
				record.id AS id,
				record.idCategory AS idCategory,
				record.active AS active,
				record.image AS image,
				language.title AS title,
				language.language AS language
				FROM
				" . DB_PREFIX . "gallery record,
				" . DB_PREFIX . "gallery_languages language
				WHERE
				record.id = $id AND
				language.idPhoto = $id AND
				language.language = '$language'
				LIMIT 1");
			
			$result = $record->fetch();
		}
		
		return $result;
	}
	
	public function editRecord($id, $idCategory, $title, $image, $active, $language)
	{
		$id = (int) $id;
		
		if ($image) {
			$this->db->query("UPDATE " . DB_PREFIX . "gallery SET image = '$image' WHERE id = $id");
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "gallery SET idCategory = '$idCategory', active = '$active' WHERE id = $id");
		$this->db->query("UPDATE " . DB_PREFIX . "gallery_languages SET title = '$title' WHERE idPhoto = $id AND language = '$language'");
	}
	
	public function deleteRecord($id)
	{
		$id = (int) $id;

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery WHERE id = $id");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_languages WHERE idPhoto = $id");
	}
}
?>
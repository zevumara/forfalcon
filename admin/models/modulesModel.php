<?php
class modulesModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function getModules()
	{
		$modules = $this->db->query("SELECT * FROM " . DB_PREFIX . "modules ORDER BY position DESC");
		
		return $modules->fetchAll();
	}
	
	public function upRecord($id, $currentPosition)
	{
		$upperRecord = $this->db->query("SELECT id,position FROM " . DB_PREFIX . "modules WHERE position > $currentPosition ORDER BY position ASC LIMIT 1");
		$record = $upperRecord->fetch();
		
		if ($record)
		{
			$this->db->query("UPDATE " . DB_PREFIX . "modules SET position = " . $record['position'] . " WHERE id = $id");
			$this->db->query("UPDATE " . DB_PREFIX . "modules SET position = " . $currentPosition . " WHERE id = " . $record['id'] . "");
		}
	}
	
	public function downRecord($id, $currentPosition)
	{
		$lowerRecord = $this->db->query("SELECT id,position FROM " . DB_PREFIX . "modules WHERE position < $currentPosition ORDER BY position DESC LIMIT 1");
		$record = $lowerRecord->fetch();

		if ($record)
		{
			$this->db->query("UPDATE " . DB_PREFIX . "modules SET position = " . $record['position'] . " WHERE id = $id");
			$this->db->query("UPDATE " . DB_PREFIX . "modules SET position = " . $currentPosition . " WHERE id = " . $record['id'] . "");
		}
	}
}
?>
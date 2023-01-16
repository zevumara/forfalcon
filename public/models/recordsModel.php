<?php
class recordsModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function newRecord($nickname, $email, $dkey)
	{
		$this->db->query("INSERT INTO " . DB_PREFIX . "mailing (id, nickname, email, dkey, status) VALUES (null, '$nickname', '$email', '$dkey', '1')");

		return $this->db->lastInsertId();
	}
	
	public function getUserByEmail($email)
	{
		$record = $this->db->query("SELECT
				record.id AS id,
				record.nickname AS nickname,
				record.dkey AS dkey
				FROM
				" . DB_PREFIX . "mailing record
				WHERE
				record.email = '$email'
				LIMIT 1");

		return $record->fetch();
	}
	
	public function validateDownload($id)
	{
		$record = $this->db->query("SELECT
				record.dkey AS dkey
				FROM
				" . DB_PREFIX . "mailing record
				WHERE
				record.id = '$id'
				LIMIT 1");

		return $record->fetch();
	}
	
	public function newDownload($id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "mailing SET downloads = downloads + 1 WHERE id = $id");
	}
}
?>
<?php
class usersModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function getUsers()
	{
		$users = $this->db->query("SELECT * FROM " . DB_PREFIX . "users LIMIT 1");
		
		return $users->fetchall();
	}
}
?>
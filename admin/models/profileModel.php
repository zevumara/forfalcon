<?php
class profileModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function getProfile($id)
	{
		$id = (int) $id;
		$profile = $this->db->query("SELECT name,username,password,email FROM " . DB_PREFIX . "users WHERE id = $id");
		
		return $profile->fetch();
	}
	
	public function updateName($id, $name)
	{
		$this->db->prepare("UPDATE " . DB_PREFIX ."users SET name = :name WHERE id = :id")
		->execute(array(
			':id' 	=> $id,
			':name' => $name
		));
	}
	
	public function updateEmail($id, $email)
	{
		$this->db->prepare("UPDATE " . DB_PREFIX ."users SET email = :email WHERE id = :id")
		->execute(array(
			':id' 	=> $id,
			':email' => $email
		));
	}
	
	public function updateUsername($id, $username)
	{
		$this->db->prepare("UPDATE " . DB_PREFIX ."users SET username = :username WHERE id = :id")
		->execute(array(
			':id' 	=> $id,
			':username' => $username
		));
	}
	
	public function updatePassword($id, $password)
	{
		$crypt_hash = crypt($password, CRYPT_HASH);
		
		$this->db->prepare("UPDATE " . DB_PREFIX ."users SET password = :password WHERE id = :id")
		->execute(array(
			':id' 	=> $id,
			':password' => $crypt_hash
		));
	}
}
?>
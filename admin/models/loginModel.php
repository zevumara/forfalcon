<?php
class loginModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getUser($username, $password)
	{
		$password = crypt($password, CRYPT_HASH);
		
		$user = $this->db->prepare("SELECT id,username,access,active FROM " . DB_PREFIX . "users WHERE username = :username AND password = :password LIMIT 1");
		$user->execute(
			array(
				':username' => $username,
				':password' => $password
			)
		);
		
		return $user->fetch();
	}
}
?>
<?php
/**
 * class Session
 * Session variables, access restrictions, user ranks.
 */
class Session
{
	/**
	 * Starts session.
	 */
	public static function init()
	{
		session_start();
	}

	/**
	 * Destroy sessions variables.
	 * 
	 * @param	string array		$key Session variables name(s) to destroy
	 */
	public static function destroy($key = false)
	{
		if ($key) {
			if (is_array($key)) {
				for ($i = 0; $i <= count($key); $i++) {
					if (isset($_SESSION[$key[$i]])) {
						unset($_SESSION[$key[$i]]);
					}
				}
			} else {
				if (isset($_SESSION[$key])) {
					unset($_SESSION[$key]);
				}
			}
		} else {
			session_destroy();
		}
	}

	/**
	 * Set session variable.
	 * 
	 * @param	string		$key Key
	 * @param	mixed		$value Value
	 */
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Get session variable.
	 * 
	 * @param	string		$key Key
	 * @return	mixed		$_SESSION content.
	 */
	public static function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}
	}

	/**
	 * Restricts access.
	 * 
	 * @param	string		$rank Approved rank name
	 */
	public static function restricAccess($rank)
	{
		if (!Session::get('authenticated')) {
			header('Location: ' . URL . 'error/index/5/');
			exit;
		}

		if (Session::getRank($rank) > Session::getRank(Session::get('rank'))) {
			header('Location: ' . URL . 'error/index/5/');
			exit;
		}
	}

	/**
	 * Partial resctriction.
	 * 
	 * @param	string		$rank Approved rank name
	 * @return	bool		Returns true if approved.
	 */
	public static function restricAccessView($rank)
	{
		if (!Session::get('authenticated')) {
			return false;
		}

		if (Session::getRank($rank) > Session::getRank(Session::get('rank'))) {
			return false;
		}

		return true;
	}

	/**
	 * Returns rank level.
	 * 
	 * @param	string		$rank Rank name
	 * @return	int			Rank level
	 */
	public static function getRank($rank)
	{
		$ranks['supra'] = 7;
		$ranks['administrator'] = 6;
		$ranks['moderator'] = 2;
		$ranks['user'] = 1;

		if (!array_key_exists($rank, $ranks)) {
			throw new Exception("Access error. Invalid rank.");
		} else {
			return $ranks[$rank];
		}
	}
}
?>
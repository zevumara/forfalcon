<?php
class menuModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function build()
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "modules ORDER BY position DESC");
		$items = $query->fetchAll();

		$main_menu = array();
		
		foreach ($items as $item) {
			$moduleName = $item['name'] . 'Module';
			$menu_file = ROOT_PATH . 'modules' . DIRECTORY_SEPARATOR . $moduleName . DIRECTORY_SEPARATOR . 'menu.php';
			
			if (is_readable($menu_file)) {
				include_once $menu_file;			
				$main_menu[] = $menu;
			}
		}

		return $main_menu;
	}
}
?>
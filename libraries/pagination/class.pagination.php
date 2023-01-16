<?php
/**
 * class Pagination
 * Generates a paged menu and the parameters (start and limit) for consult to database.
 * 
 * @author	zevamaru <sebastian@zevamaru.com>
 * @license	http://opensource.org/licenses/MIT	The MIT License (MIT)
 */
class Pagination
{
	public $start;
	public $limit;
	public $current_page;
	public $total_pages;
	public $total_records;
	public $first_page;
	public $last_page;
	public $prev_page;
	public $next_page;
	
	/**
	 * Checks the parameters and call _paginate method.
	 * 
	 * @param	int		$total_records Number of records (count)
	 * @param	int		$page optional Current page
	 * @param	int		$limit optional Limit of records
	 * @access	public
	 */
	public function __construct($total_records, $page = 1, $limit = 10)
	{		
		if (!is_numeric($page) || $page < 1)
		{
			$page = 1;
		}
		
		if (!is_numeric($limit) || $limit < 1)
		{
			$limit = 10;
		}
		
		$this->_paginate($total_records, $page, $limit);
	}
	
	/**
	 * Calculates and saves the pagination parameters.
	 * 
	 * @param	int		$total_records Number of records (count)
	 * @param	int		$page Current page
	 * @param	int		$limit Limit of records
	 * @access	private
	 */
	private function _paginate($total_records, $page, $limit)
	{		
		// Calcualtes from where start to call records according to the current page.
		$start = ($page - 1) * $limit;
		
		// Total number of pages.
		$total_pages = ceil($total_records / $limit);
		
		// Pagination parameters.
		$this->start = $start;
		$this->limit = $limit;
		$this->current_page = $page;
		$this->total_pages = $total_pages;
		$this->total_records = $total_records;
		$this->prev_page = false;
		$this->next_page = false;
		
		if ($page > 1)
		{
			$this->prev_page = $page - 1;
		}
		
		if ($page < $total_pages)
		{
			$this->next_page = $page + 1;
		}
	}
	
	/**
	 * Gets the paged menu.
	 * 
	 * @param	string		$link Url for the links
	 * @param	string		$view optional Name of the view file
	 * @return	string		Paged menu
	 * @access	public
	 */
	public function get($link = false, $view = 'default')
	{
        $view_file =  dirname(__FILE__) . '/views/' . $view . '.phtml';
		
		// If the view file is valid, saves and returns it's content.
		if (is_readable($view_file))
		{
			ob_start();
			
			include $view_file;
			
			$content = ob_get_contents();
			
			ob_end_clean();
			
			return $content;
		}
		else
		{
			echo "Error: '" . $view_file . "' not found.";
			exit;
		}
	}
}
?>
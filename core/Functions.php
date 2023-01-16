<?php
/**
 * class Functions
 * Contains utility functions.
 */
abstract class Functions
{	
	/**
	 * Get a PHP library.
	 * 
	 * @param	string		$library Library filename without extension
	 * @access	protected
	 */
	protected function getPhpLibrary($library)
	{
		$library_file = ROOT_PATH . 'libraries' . DIRECTORY_SEPARATOR . $library . '.php';
		
		// If library is valid...
		if (is_readable($library_file)) {
			// Includes library
			require_once $library_file;
		} else {
			throw new Exception("Library file '$library_file' not found.");
		}
	}
	
	/**
	 * Returns $_POST variable without any filter.
	 * 
	 * @param	string		$key $_POST key
	 * @return	mixed		$_POST value
	 * @access	protected
	 */
	protected function getPost($key)
	{
		if (isset($_POST[$key])) {
			return $_POST[$key];
		}
	}
	
	/**
	 * Sanitize $_POST variable with text.
	 * 
	 * @param	string		$key $_POST key
	 * @return	string		Returns filtered variable $_POST
	 * @access	protected
	 */
	protected function getPostString($key)
	{
		if (isset($_POST[$key]) && !empty($_POST[$key])) {
			$_POST[$key] = htmlspecialchars($_POST[$key], ENT_QUOTES);
			
			return $_POST[$key];
		}
	}
	
	/**
	 * Sanitize $_POST variable with integer.
	 * 
	 * @param	string		$key $_POST key
	 * @return	string		Returns filtered variable $_POST
	 * @access	protected
	 */
	protected function getPostInt($key)
	{
		if (isset($_POST[$key]) && !empty($_POST[$key])) {
			$_POST[$key] = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
			
			return $_POST[$key];
		}
	}
	
	/**
	 * Security function for sanitize variables with integers.
	 * 
	 * @param	mixed
	 * @return	int			If can't be transformed returns 0
	 * @access	protected
	 */
	protected function getInt($int)
	{
		$int = (int) $int;
		
		if (is_int($int)) {
			return $int;
		} else {
			return 0;
		}
	}
	
	/**
	 * Uploads a file.
	 * 
	 * @param	string		$key $_FILES key
	 * @return	string		Filename
	 * @access	protected
	 */
	protected function uploadFile($key)
	{
		if ($_FILES[$key]['name']) {
			// Get library
			$this->getPhpLibrary('upload' . DIRECTORY_SEPARATOR . 'class.upload');
			
			$file = new upload($_FILES[$key]);
			
			// Filename
			$filename = explode('.', $_FILES[$key]['name']);
			$filename = $this->cleanString($filename[0]) . '_' . uniqid();
			$file->file_new_name_body = $filename;
			
			// Upload
			$file->process(FILES_PATH);
			
			// Success or failed?
			if ($file->processed) {
				$file = $file->file_dst_name;
			} else {
				throw new Exception($file->error);
			}
		} else {
			$file = '';
		}
		
		return $file;
	}
	
	/**
	 * Uploads an image and creates up to five thumbnails.
	 * 
	 * @param	string		$key $_FILES key
	 * @param	array		$thumbnail (int $with, int $height, bool optional $autodetect)
	 * @return	string		Image filename
	 * @access	protected
	 */
	protected function uploadImage($key, $thumbnail1 = false, $thumbnail2 = false, $thumbnail3 = false, $thumbnail4 = false, $thumbnail5 = false)
	{
		if ($_FILES[$key]['name']) {
			// Get library
			$this->getPhpLibrary('upload' . DIRECTORY_SEPARATOR . 'class.upload');

			$file = new \Verot\Upload\Upload($_FILES[$key]);
			
			// Only accept images
			$file->allowed = array('image/*');
			
			// Image name
			$filename = explode('.', $_FILES[$key]['name']);
			$filename = $this->cleanString($filename[0]) . '_' . uniqid();
			$file->file_new_name_body = $filename;
			
			// Upload
			$file->process(IMAGES_PATH);
			
			// Success or failed?
			if ($file->processed) {
				// Image name processed.
				$image = $file->file_dst_name;
				
				// Internal thumb
				if (APPLICATION == 'admin') {
					$this->createThumbImage($file, array(104, false, false), INTERNAL_THUMBNAILS_PATH);
				}				
				// Thumbs 1
				if ($thumbnail1) {
					$this->createThumbImage($file, $thumbnail1, THUMBNAILS_1_PATH);
				}				
				// Thumbs 2
				if ($thumbnail2) {
					$this->createThumbImage($file, $thumbnail2, THUMBNAILS_2_PATH);
				}
			} else {
				throw new Exception($file->error);
			}
		} else {
			$image = '';
		}
		
		return $image;
	}

	/**
	 * Creates a thumbnail from an image. Requires class.upload.php library.
	 * 
	 * @param	object		$source	Image uploaded
	 * @param	array		$thumbnail (int $with, int $height, bool optional $autodetect)
	 * @param	string		$path Path to save the thumbnail
	 * @access	protected
	 */
	protected function createThumbImage($source, $thumbnail, $path)
	{
		// Get thumbnail values
		list($t_width, $t_height, $autodetect) = $thumbnail;
		
		$thumbnail = new \Verot\Upload\Upload($source->file_dst_pathname);
		$thumbnail->image_resize = true;
		
		// Detects if it's a vertical image or an horizontal image
		if ($autodetect) {
			// Original dimensions
			$width = $source->image_dst_x;
			$height = $source->image_dst_y;
			
			// Horizontal image (redimension from width)
			if ($width >= $height) {
				$thumbnail->image_x = $t_width;
				$thumbnail->image_ratio_y = true;
			// Vertical image (redimension from height)
			} else {
				$thumbnail->image_y = $t_height;
				$thumbnail->image_ratio_x = true;
			}
		// Redimension the image manually
		} else {
			// If is set with and height, deforms the image
			if ($t_width != 0 && $t_height != 0) {
				$thumbnail->image_x = $t_width;
				$thumbnail->image_y = $t_height;
			// If only set the width, maintains the proportions
			} else if ($t_width != 0) {
				$thumbnail->image_x = $t_width;
				$thumbnail->image_ratio_y = true;
			// If only set the height, maintains the proportions
			} else {
				$thumbnail->image_y = $t_height;
				$thumbnail->image_ratio_x = true;
			}
		}
		// Creates thumb
		$thumbnail->process($path);
	}
	
	/**
	 * Deletes a file.
	 * 
	 * @param	string		$filename Filename
	 * @access	protected
	 */
	protected function deleteFile($filename)
	{
		$paths = array(INTERNAL_THUMBNAILS_PATH, IMAGES_PATH, THUMBNAILS_1_PATH, THUMBNAILS_2_PATH, THUMBNAILS_3_PATH, THUMBNAILS_4_PATH, THUMBNAILS_5_PATH, FILES_PATH, VIDEOS_PATH);
		
		foreach ($paths as $path) {
			if (file_exists($path . $filename) && !is_dir(file_exists($path . $filename))) {
				@unlink($path . $filename);
			}
		}
	}

	/**
	 * Removes special characters from a string.
	 * 
	 * @param	string
	 * @return	string		Returns a string without special characters
	 * @access	protected
	 */
	protected function cleanString($string)
	{
		$string = str_replace(array('À','Á','Â','Ã','Ä','Å','à','á','â','ã','ä','å','Ò','Ó','Ô','Õ','Ö','Ø','ò','ó','ô','õ','ö','ø','È','É','Ê','Ë','è','é','ê','ë','Ç','ç','Ì','Í','Î','Ï','ì','í','î','ï','Ù','Ú','Û','Ü','ù','ú','û','ü','ÿ','Ñ','ñ',' ',',','(',')'),array('A','A','A','A','A','A','a','a','a','a','a','a','O','O','O','O','O','O','o','o','o','o','o','o','E','E','E','E','e','e','e','e','C','c','I','I','I','I','i','i','i','i','U','U','U','U','u','u','u','u','y','N','n','-','','',''), $string);
		$string = strtolower($string);
		return $string;
	}
	
	/**
	 * Returns true if it's a valid email or false if it's not valid.
	 * 
	 * @param	string		$email
	 * @return	bool
	 * @access	protected
	 */
	protected function validateEmail($email)
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Redirection.
	 * 
	 * @param	string		$path optional
	 * @param	string		$base_url optional
	 * @access	protected
	 */
	protected function redirect($path = false, $url = URL)
	{		
		if ($path) {
			header("Location: " . $url . $path);
			exit;
		} else {
			header("Location: " . $url);
			exit;
		}
	}
	
	/**
	 * Redirection via Javascript.
	 * 
	 * @param	string		$path
	 * @access	protected
	 */
	protected function redirectJs($path = false, $url = URL)
	{		
		if ($path) {
			echo '<p>Redirecting... please wait.</p>';
			echo '<script>window.parent.location.href = " ' . $url . $path . '";</script>';
			exit;
		}
	}
}
?>
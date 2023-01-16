<?php
class setupController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Configuration.php
	 */
	private function _configuration_file($root_url, $website, $db_host, $db_user, $db_password, $db_name, $db_prefix, $language, $crypt_hash)
	{	
        $content_file  = "<?php\n";
        $content_file .= "// Root url\n";
        $content_file .= "define('ROOT_URL', '$root_url');\n";
        $content_file .= "\n";
        $content_file .= "// Website name\n";
        $content_file .= "define('WEBSITE', '$website');\n";
        $content_file .= "\n";
        $content_file .= "// Database connection\n";
        $content_file .= "define('DB_HOST', '$db_host');\n";
        $content_file .= "define('DB_USER', '$db_user');\n";
        $content_file .= "define('DB_PASSWORD', '$db_password');\n";
        $content_file .= "define('DB_NAME', '$db_name');\n";
        $content_file .= "define('DB_PREFIX', '$db_prefix');\n";
        $content_file .= "define('DB_CHARSET', 'utf8');\n";
        $content_file .= "\n";
        $content_file .= "// Default language\n";
        $content_file .= "\$__lang = '$language';\n";
        $content_file .= "\n";
        $content_file .= "// For passwords encryptation\n";
        $content_file .= "define('CRYPT_HASH', '$crypt_hash');\n";
        $content_file .= "?>";
        return $content_file;
    }
	
	/**
	 * Form validation.
	 */
	private function _form_data()
	{		
		// Default database host
		$this->view->db_host = 'localhost';
		
		if ($this->getPost('DB_HOST')) {
			$this->view->db_host = $this->getPost('DB_HOST');
		} else if (defined('DB_HOST')) {
			$this->view->db_host = DB_HOST;
		}
		
		// Default database name
		$this->view->db_name = 'forfalcon';
		
		if ($this->getPost('DB_NAME')) {
			$this->view->db_name = $this->getPost('DB_NAME');
		}else if (defined('DB_NAME')) {
			$this->view->db_name = DB_NAME;
		}
		
		// Default database prefix
		$this->view->db_prefix = '';
		
		if ($this->getPost('DB_PREFIX')) {
			$this->view->db_prefix = $this->getPost('DB_PREFIX');
		}else if (defined('DB_PREFIX')) {
			$this->view->db_prefix = DB_PREFIX;
		}
		
		// Default database user
		$this->view->db_user = 'docker';
		
		if ($this->getPost('DB_USER')) {
			$this->view->db_user = $this->getPost('DB_USER');
		}else if (defined('DB_USER')) {
			$this->view->db_user = DB_USER;
		}
		
		// Default database password
		$this->view->db_password = 'local';
		
		if ($this->getPost('DB_PASSWORD')) {
			$this->view->db_password = $this->getPost('DB_PASSWORD');
		} else if (defined('DB_PASSWORD')) {
			$this->view->db_password = DB_PASSWORD;
		}
		
		// Default root url
		$this->view->root_url = 'http://localhost/';
		
		if ($this->getPost('root_url')) {
			$this->view->root_url = $this->getPost('root_url');
		} else if (defined('ROOT_URL')) {
			$this->view->root_url = ROOT_URL;
		}
		
		// Default website name
		$this->view->website = 'Forfalcon';
		
		if ($this->getPost('website')) {
			$this->view->website = $this->getPost('website');
		}
		
		// Default username
		$this->view->username = 'admin';
		
		if ($this->getPost('username')) {
			$this->view->username = $this->getPost('username');
		}
		
		// Default name
		$this->view->name = 'Sebastián Cámara';
		
		if ($this->getPost('name')) {
			$this->view->name = $this->getPost('name');
		}
		
		// Default email
		$this->view->email = 'sebastian@zevamaru.com';
		
		if ($this->getPost('email')) {
			$this->view->email = $this->getPost('email');
		}
		
		// Default password
		$this->view->password = 'admin';
		
		if ($this->getPost('password')) {
			$this->view->password = $this->getPost('password');
		}
	}
	
	/**
	 * Creates configuration file, tables and database records.
	 * 
	 * @return	boolean		Returns true if the process was successful
	 */
	private function _install()
	{
		if ($this->getPostInt('start_installation') != 1) {
			return false;
		}
		
		if ($this->getPostInt('start_installation') === 1) {
			if (!$this->getPost('DB_HOST') || !$this->getPost('DB_USER') || !$this->getPost('DB_PASSWORD') || !$this->getPost('DB_NAME') || !$this->getPost('root_url')) {
				$this->view->_warning = $this->view->__localization['MESSAGE'][7];
				return false;
			}
			
			// Language
			$language = array('en_US', 'English');
			
			// Crypt hash
			$crypt_hash = '$2x$07$.e14Af7xe.i1fSaXLsk/34$';

			// Encrypts the password
			$password = crypt($this->getPostString('password'), $crypt_hash);
		
			// Configuration.php
			$content_file  = $this->_configuration_file($this->getPostString('root_url'), $this->getPostString('website'), $this->getPostString('DB_HOST'),
            $this->getPostString('DB_USER'), $this->getPostString('DB_PASSWORD'), $this->getPostString('DB_NAME'), $this->getPostString('DB_PREFIX'), $language[0], $crypt_hash);
			
			// Writes file
			$file = fopen(CORE_PATH . 'Configuration.php', 'w');
			if (!isset($file) || !fwrite($file, $content_file)){
				$this->view->_error = $this->view->__localization['MESSAGE'][8];
				return false;
			}
			fclose($file);
			
			// Check database connection...
			try {
				$singleton = Singleton::get();
				$singleton->db = new Database($this->getPostString('DB_HOST'), $this->getPostString('DB_NAME'), $this->getPostString('DB_USER'), $this->getPostString('DB_PASSWORD'), 'utf8');
			} catch (PDOException $exception) {
				$this->view->_error = $this->view->__localization['MESSAGE'][6];
				return false;
			}

			// Creates tables and records
			try {
				$this->_database = $this->loadModel('setup');			
				if ($this->_database->isInstalled()) return true;
				$this->_database->install($this->getPostString('username'), $password, $this->getPostString('name'), $this->getPost('email'), $language, $this->getPostString('DB_PREFIX'));
			} catch (PDOException $exception) {
				$this->view->_error = $exception;
				return false;
			}
		}		
		return true;
	}
	
	/**
	 * Checks if installed, and shows the installation form.
	 */
	public function index()
	{	
		try {
			$singleton = Singleton::get();
			$singleton->db = new Database;

			$model = $this->loadModel('setup');
			$installed = $model->isInstalled();
			
			if ($installed) {
				// Installation process succeed
				$this->view->title = $this->view->__localization['CONTENT_MANAGEMENT_SYSTEM'];
				$this->view->render('done', true);
			} else {
				// Installation form
				$this->install();
			}
		}
		catch (PDOException $exception) {
			// Installation form
			$this->install();
		}
	}
	
	/**
	 * Installation form.
	 */
	public function install()
	{
		$this->_form_data();
		
		if ($this->_install()) {
			$this->redirect();
		}

		$this->view->title = $this->view->__localization['CONTENT_MANAGEMENT_SYSTEM'];
		$this->view->render('index', true);
	}
}
?>
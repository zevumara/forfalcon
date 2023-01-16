<?php
/**
 * class loginController
 * For administrators environments or login required environments, this class checks if the admin is logged. If not, shows the form.
 */
class loginController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Makes all validations.
	 * 
	 * @return	bool		Returns true or false depending if is logged like administator or not
	 * @access	private
	 */
	private function _logged()
	{
		// If not logged and not trying to login returns false
		if (!Session::get('authenticated') && $this->getPostInt('login') != 1) {
			return false;
		}

		// If not logged and are trying to login, checks data
		if (!Session::get('authenticated') && $this->getPostInt('login') == 1) {
			$this->view->post = $_POST;

			if (!$this->getPost('username')) {
				$this->view->_error = $this->view->__localization['MESSAGE'][1];
				return false;
			}

			if (!$this->getPost('password')) {
				$this->view->_error = $this->view->__localization['MESSAGE'][2];
				return false;
			}

			$this->_login = $this->loadModel('login');
			$user = $this->_login->getUser($this->getPost('username'), $this->getPost('password'));

			if (!$user) {
				$this->view->_error = $this->view->__localization['MESSAGE'][3];
				return false;
			}

			if ($user['active'] != 1) {
				$this->view->_error = $this->view->__localization['MESSAGE'][4];
				return false;
			}

			if ($user['access'] != 'administrator') {
				$this->view->_error = $this->view->__localization['MESSAGE'][5];
				return false;
			}

			// The data is correct. Logging in...			
			Session::set('authenticated', true);
			Session::set('rank', $user['access']);
			Session::set('username', $user['username']);
			Session::set('id', $user['id']);
		}

		// If is logged but is not administrator, destroy the session and returns false
		if (Session::get('authenticated') && Session::get('rank') != 'administrator') {
			$this->view->_error = $this->view->__localization['MESSAGE'][5];
			Session::destroy();
			return false;
		}

		return true;
	}

	public function list()
	{
		if ($this->_logged()) {
			$this->redirect();
		}

		$this->view->title = $this->view->__localization['LOGIN'];
		$this->view->render('index', true);
	}

	public function logout()
	{
		Session::destroy();
		$this->redirect();
	}
}
?>
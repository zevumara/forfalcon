<?php
class profileController extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->_profile = $this->loadModel('profile');
	}

	/**
	 * Autocomplete fields.
	 */
	private function _autocomplete($profile)
	{
		$this->view->p_email = $profile['email'];

		if ($this->getPost('email')) {
			$this->view->p_email = $this->getPost('email');
		}

		$this->view->p_username = $profile['username'];

		if ($this->getPost('username')) {
			$this->view->p_username = $this->getPost('username');
		}

		$this->view->p_name = $profile['name'];

		if ($this->getPost('name')) {
			$this->view->p_name = $this->getPost('name');
		}
	}

	public function list()
	{
		$this->view->title = $this->view->__localization['PROFILE_CONFIGURATION'];

		$id = Session::get('id');
		$profile = $this->_profile->getProfile($id);
		$this->_autocomplete($profile);

		if ($this->getPostInt('save')) {

			// Update e-mail
			if ($this->getPost('email') != $profile['email']) {
				if (!$this->validateEmail($this->getPost('email'))) {
					$this->view->_error = $this->view->__localization['MESSAGE'][13];
					$this->view->render('index', 'profile');
					exit;
				}

				$password = crypt($this->getPostString('password'), CRYPT_HASH);

				if ($password != $profile['password']) {
					$this->view->_error = $this->view->__localization['MESSAGE'][14];
					$this->view->render('index', 'profile');
					exit;
				}

				$this->_profile->updateEmail($id, $this->getPost('email'));
			}

			// Update username
			if ($this->getPost('username') != $profile['username']) {
				$password = crypt($this->getPostString('password'), CRYPT_HASH);

				if ($password != $profile['password']) {
					$this->view->_error = $this->view->__localization['MESSAGE'][14];
					$this->view->render('index', 'profile');
					exit;
				}

				$this->_profile->updateUsername($id, $this->getPost('username'));
				Session::set('username', $this->getPost('username'));
			}

			// Update password
			if ($this->getPost('newPassword')) {
				$password = crypt($this->getPostString('password'), CRYPT_HASH);

				if ($password != $profile['password']) {
					$this->view->_error = $this->view->__localization['MESSAGE'][14];
					$this->view->render('index', 'profile');
					exit;
				}

				if ($this->getPost('newPassword') != $this->getPost('repeatNewPassword')) {
					$this->view->_error = $this->view->__localization['MESSAGE'][15];
					$this->view->render('index', 'profile');
					exit;
				}

				$this->_profile->updatePassword($id, $this->getPost('newPassword'));
			}

			// Update name
			$this->_profile->updateName($id, $this->getPostString('name'));
			$this->view->_success = $this->view->__localization['MESSAGE'][12];
		}

		$this->view->render('index', 'profile');
	}
}
?>
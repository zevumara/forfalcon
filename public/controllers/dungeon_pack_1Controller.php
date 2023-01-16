<?php
class dungeon_pack_1Controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if ($this->getPostInt('save'))
		{
			$records = $this->loadModel('records');

			$nickname = $this->getPostString('nickname');
			$email = $this->getPost('email');

			if ($this->validateEmail($email))
			{
				$result = $records->getUserByEmail($email);

				if ($result)
				{
					// Old user
					$id = $result['id'];
					$nickname = $result['nickname'];
					$dkey = $result['dkey'];
				}
				else
				{	
					// New user

					function uuidv4()
					{
						return implode('', [
							bin2hex(random_bytes(4)),
							bin2hex(random_bytes(2)),
							bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
							bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
							bin2hex(random_bytes(6))
						]);
					}					
					$dkey = uuidv4(); // 32 characters

					$id = $records->newRecord(
						$nickname,
						$email,
						$dkey
					);
				}

				$url = "https://hitaitaro.com/dungeon-pack-1/download/" . $id . "/" . $dkey . "/";
				$html = file_get_contents(FILES_PATH . 'index.html');
				$parse_html = str_replace("{nickname}", $nickname, $html);
				$content = str_replace("{link}", $url, $parse_html);
				
				// Get library.
				$this->getPhpLibrary('phpmailer' . DIRECTORY_SEPARATOR . 'class.phpmailer');
				
				$mail = new PHPMailer;
				$mail->isSMTP();
				$mail->Host = 'c2390293.ferozo.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'sebastian@hitaitaro.com';
				$mail->Password = '10S41e98b8a';
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
				$mail->CharSet = 'UTF-8';
				$mail->isHTML(true);
				$mail->setFrom('sebastian@hitaitaro.com', 'Sebastián');
				$mail->addAddress($email, $nickname);
				$mail->Subject = $nickname . ' here is your download link';               
				$mail->Body = $content;
				$mail->AltBody = 'Este es el mensaje alternativo para clientes de correo que no usan HTML';  

				if(!$mail->send())
				{
					$this->view->_success = "The email could not be sent, please try again.";
				}
				else
				{
					$this->view->_success = "The download link was sent to your e-mail successfully.";
				}
			}

		}

		$this->view->title = "Dungeon Pack #1 - The Return of Alex";
		$this->view->render('index');
	}
	
	public function download($id, $dkey)
	{
		$records = $this->loadModel('records');
		$result = $records->validateDownload($id);

		if ($result['dkey'] == $dkey)
		{
			$records->newDownload($id);
			$this->redirect('uploads/files/Dungeon_Pack_1_The_Return_of_Alex.zip');
		}
		else
		{
			$this->redirect('error/');
		}
	}
}
?>